<?php

namespace App\Actions\GetFlightDetail;

use App\Exceptions\CannotAccessFlightDetailException;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class GetFlightDetailAction
{
    public static function handle(GetFlightDetailActionInput $input): GetFlightDetailActionOutput
    {
        $flight = Flight::query()->findOrFail($input->flightId);

        $user = User::query()->findOrFail($input->userId);

        if (Gate::forUser($user)->denies('view-flight', $flight)) {
            throw new CannotAccessFlightDetailException();
        }

        return new GetFlightDetailActionOutput(
            flightId: $flight->id,
            status: $flight->status,
            destination: $flight->destination,
            departuneDate: $flight->departune_date,
            returnDate: $flight->return_date,
            createdAt: $flight->created_at,
            updatedAt: $flight->updated_at,
        );
    }
}
