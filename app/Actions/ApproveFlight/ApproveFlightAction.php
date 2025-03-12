<?php

namespace App\Actions\ApproveFlight;

use App\Enums\FlightStatusEnum;
use App\Events\FlightApprovedEvent;
use App\Exceptions\CannotApproveFlightHasAlreadyBeenApprovedException;
use App\Exceptions\CannotApproveFlightWasCancelledException;
use App\Exceptions\CannotApproveTheirOwnFlightRequestException;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ApproveFlightAction
{
    public static function handle(ApproveFlightActionInput $input): void
    {
        $flight = Flight::query()->findOrFail($input->flightId);

        if ($flight->isApproved()) {
            throw new CannotApproveFlightHasAlreadyBeenApprovedException();
        }

        if ($flight->isCancelled()) {
            throw new CannotApproveFlightWasCancelledException();
        }

        $approver = User::query()->findOrFail($input->userApproverId);

        if (Gate::forUser($approver)->denies('approve-flight', $flight)) {
            throw new CannotApproveTheirOwnFlightRequestException();
        }

        $flight->update([
            'status' => FlightStatusEnum::APPROVED->value
        ]);

        FlightApprovedEvent::dispatch($flight);
    }
}
