<?php

namespace Tests\Feature\Actions;

use App\Actions\ApproveFlight\ApproveFlightAction;
use App\Actions\ApproveFlight\ApproveFlightActionInput;
use App\Enums\FlightStatusEnum;
use App\Events\FlightApprovedEvent;
use App\Exceptions\CannotApproveFlightHasAlreadyBeenApprovedException;
use App\Exceptions\CannotApproveFlightWasCancelledException;
use App\Exceptions\CannotApproveTheirOwnFlightRequestException;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ApproveFlightActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_approve_flight_throws_flight_model_not_found_exception(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $input = new ApproveFlightActionInput(
            flightId: 1,
            userApproverId: 1
        );

        ApproveFlightAction::handle($input);
    }

    public function test_approve_flight_throws_user_approver_model_not_found_exception(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $flight = Flight::factory()->for(User::factory()->create())->create();

        $input = new ApproveFlightActionInput(
            flightId: $flight->id,
            userApproverId: -1
        );

        ApproveFlightAction::handle($input);
    }

    public function test_approve_flight_throws_cannot_approve_flight_already_approved_exception(): void
    {
        $this->expectException(CannotApproveFlightHasAlreadyBeenApprovedException::class);

        $flight = Flight::factory()->for(User::factory()->create())->approved()->create();

        $input = new ApproveFlightActionInput(
            flightId: $flight->id,
            userApproverId: $flight->user_id
        );

        ApproveFlightAction::handle($input);
    }

    public function test_approve_flight_throws_cannot_approve_flight_thats_cancelled_exception(): void
    {
        $this->expectException(CannotApproveFlightWasCancelledException::class);

        $flight = Flight::factory()->for(User::factory()->create())->canceled()->create();

        $input = new ApproveFlightActionInput(
            flightId: $flight->id,
            userApproverId: $flight->user_id
        );

        ApproveFlightAction::handle($input);
    }

    public function test_approve_flight_throws_requester_cant_approve_own_flight_exception(): void
    {
        $this->expectException(CannotApproveTheirOwnFlightRequestException::class);

        $flight = Flight::factory()
            ->for(
                factory: User::factory()->create()
            )
            ->create();

        $input = new ApproveFlightActionInput(
            flightId: $flight->id,
            userApproverId: $flight->user_id
        );

        ApproveFlightAction::handle($input);
    }

    public function test_approve_flight_successfull(): void
    {
        Event::fake();

        $flight = Flight::factory()->for(User::factory()->create())->create();
        $user = User::factory()->create();

        $input = new ApproveFlightActionInput(
            flightId: $flight->id,
            userApproverId: $user->id
        );

        ApproveFlightAction::handle($input);

        $this->assertDatabaseHas(
            table: 'flights',
            data: [
                'id' => $flight->id,
                'user_id' => $flight->user_id,
                'status' => FlightStatusEnum::APPROVED->value,
                'destination' => $flight->destination
            ]
        );

        Event::assertDispatched(FlightApprovedEvent::class);
        Event::assertDispatchedTimes(FlightApprovedEvent::class, 1);
    }
}
