<?php

namespace Tests\Feature\Actions;

use App\Actions\RequestFlight\RequestFlightAction;
use App\Actions\RequestFlight\RequestFlightActionInput;
use App\Actions\RequestFlight\RequestFlightActionOutput;
use App\Events\FlightRequestedEvent;
use App\Exceptions\InvalidFlightDates;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RequestFlightActionTest extends TestCase
{
    use DatabaseMigrations;

    public function test_request_flight_throws_model_not_found_exception(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $date = now()->addDay();
        $departuneDate = $date->copy();
        $returnDate = $date->copy()->addDays(2);

        $requestFlightInput = new RequestFlightActionInput(
            userId: 1,
            departuneDate: $departuneDate,
            returnDate: $returnDate
        );
        RequestFlightAction::handle($requestFlightInput);
    }

    public function test_request_flight_throws_invalid_flight_dates(): void
    {
        $this->expectException(InvalidFlightDates::class);

        $user = User::factory()->create();
        $date = now()->addDay();
        $departuneDate = $date->copy()->addDays(2);
        $returnDate = $date->copy();

        $requestFlightInput = new RequestFlightActionInput(
            userId: $user->id,
            departuneDate: $departuneDate,
            returnDate: $returnDate
        );
        RequestFlightAction::handle($requestFlightInput);
    }

    public function test_request_flight_successfull(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $date = now()->addDay();
        $departuneDate = $date->copy();
        $returnDate = $date->copy()->addDays(2);

        $requestFlightInput = new RequestFlightActionInput(
            userId: $user->id,
            departuneDate: $departuneDate,
            returnDate: $returnDate
        );
        $output = RequestFlightAction::handle($requestFlightInput);

        $this->assertInstanceOf(RequestFlightActionOutput::class, $output);
        $this->assertDatabaseCount(
            table: 'flights',
            count: 1
        );
        $this->assertDatabaseHas(
            table: 'flights',
            data: [
                'id' => $output->id,
                'user_id' => $user->id,
                'status' => 'REQUESTED',
            ]
        );

        Event::assertDispatched(FlightRequestedEvent::class);
        Event::assertDispatchedTimes(FlightRequestedEvent::class, 1);
    }
}
