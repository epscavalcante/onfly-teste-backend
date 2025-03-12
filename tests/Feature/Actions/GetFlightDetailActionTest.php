<?php

namespace Tests\Feature\Actions;

use App\Actions\GetFlightDetail\GetFlightDetailAction;
use App\Actions\GetFlightDetail\GetFlightDetailActionInput;
use App\Actions\GetFlightDetail\GetFlightDetailActionOutput;
use App\Exceptions\CannotAccessFlightDetailException;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetFlightDetailActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_flight_details_throws_flight_model_not_found_exception(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $input = new GetFlightDetailActionInput(
            flightId: 1,
            userId: 1,
        );
        GetFlightDetailAction::handle($input);
    }

    public function test_get_flight_details_throws_user_model_not_found_exception(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $flight = Flight::factory()->for(User::factory()->create())->create();
        $input = new GetFlightDetailActionInput(
            flightId: $flight->id,
            userId: 999999,
        );
        GetFlightDetailAction::handle($input);
    }

    public function test_get_flight_details_throws_cannot_access_flight_details_exception(): void
    {
        $this->expectException(CannotAccessFlightDetailException::class);

        $user = User::factory()->create();
        $flight = Flight::factory()->for(User::factory()->create())->create();
        $input = new GetFlightDetailActionInput(
            flightId: $flight->id,
            userId: $user->id,
        );
        GetFlightDetailAction::handle($input);
    }

    public function test_the_login_handle_successfull(): void
    {
        $flight = Flight::factory()
            ->for(
                User::factory()->create()
            )
            ->create();
        $input = new GetFlightDetailActionInput(
            flightId: $flight->id,
            userId: $flight->user_id,
        );
        $output = GetFlightDetailAction::handle($input);

        $this->assertInstanceOf(GetFlightDetailActionOutput::class, $output);
    }
}
