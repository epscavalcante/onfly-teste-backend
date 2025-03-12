<?php

namespace App\Actions\CancelFlight;

use App\Enums\FlightStatusEnum;
use App\Events\FlightCancelledEvent;
use App\Exceptions\CannotCancelFlightException;
use App\Exceptions\CannotCancelFlightHasAlreadyBeenCancelledException;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class CancelFlightAction
{
    public static function handle(CancelFlightActionInput $input): void
    {
        $flight = Flight::query()->findOrFail($input->flightId);

        if ($flight->isCancelled()) {
            throw new CannotCancelFlightHasAlreadyBeenCancelledException();
        }

        $user = User::query()->findOrFail($input->userId);

        if (Gate::forUser($user)->denies('cancel-flight', $flight)) {
            throw new CannotCancelFlightException();
        }

        $flight->update([
            'status' => FlightStatusEnum::CANCELED->value
        ]);

        FlightCancelledEvent::dispatch($flight);
    }
}
