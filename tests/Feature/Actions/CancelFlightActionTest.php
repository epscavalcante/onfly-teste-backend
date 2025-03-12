<?php

namespace Tests\Feature\Actions;

use App\Actions\CancelFlight\CancelFlightAction;
use App\Actions\CancelFlight\CancelFlightActionInput;
use App\Enums\FlightStatusEnum;
use App\Events\FlightCancelledEvent;
use App\Exceptions\CannotCancelFlightException;
use App\Exceptions\CannotCancelFlightHasAlreadyBeenCancelledException;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CancelFlightActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancel_flight_throws_flight_model_not_found_exception(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $input = new CancelFlightActionInput(
            flightId: 1,
            userId: 1
        );

        CancelFlightAction::handle($input);
    }

    public function test_cancel_flight_throws_user_cancel_model_not_found_exception(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $flight = Flight::factory()->for(User::factory()->create())->create();

        $input = new CancelFlightActionInput(
            flightId: $flight->id,
            userId: -1
        );

        CancelFlightAction::handle($input);
    }

    public function test_cancel_flight_throws_cannot_cancel_flight_already_cancelled_exception(): void
    {
        $this->expectException(CannotCancelFlightHasAlreadyBeenCancelledException::class);

        $flight = Flight::factory()->for(User::factory()->create())->canceled()->create();

        $input = new CancelFlightActionInput(
            flightId: $flight->id,
            userId: $flight->user_id
        );

        CancelFlightAction::handle($input);
    }

    public function test_cancel_flight_throws_cannot_cancel_flight__exception(): void
    {
        $this->expectException(CannotCancelFlightException::class);

        $flight = Flight::factory()->for(User::factory()->create())->approved()->create();

        $input = new CancelFlightActionInput(
            flightId: $flight->id,
            userId: $flight->user_id
        );

        CancelFlightAction::handle($input);
    }

    public function test_cancel_flight_was_requested_successfull(): void
    {
        Event::fake();

        $flight = Flight::factory()->for(User::factory()->create())->requested()->create();

        $input = new CancelFlightActionInput(
            flightId: $flight->id,
            userId: $flight->user_id
        );

        CancelFlightAction::handle($input);

        $this->assertDatabaseHas(
            table: 'flights',
            data: [
                'id' => $flight->id,
                'user_id' => $flight->user_id,
                'status' => FlightStatusEnum::CANCELED->value,
                'destination' => $flight->destination
            ]
        );

        Event::assertDispatched(FlightCancelledEvent::class);
        Event::assertDispatchedTimes(FlightCancelledEvent::class, 1);
    }

    public function test_cancel_flight_was_approved_successfull(): void
    {
        Event::fake();

        $flight = Flight::factory()->for(User::factory()->create())->approved()->create();
        $user = User::factory()->create();

        $input = new CancelFlightActionInput(
            flightId: $flight->id,
            userId: $user->id
        );

        CancelFlightAction::handle($input);

        $this->assertDatabaseHas(
            table: 'flights',
            data: [
                'id' => $flight->id,
                'user_id' => $flight->user_id,
                'status' => FlightStatusEnum::CANCELED->value,
                'destination' => $flight->destination
            ]
        );

        Event::assertDispatched(FlightCancelledEvent::class);
        Event::assertDispatchedTimes(FlightCancelledEvent::class, 1);
    }
}
