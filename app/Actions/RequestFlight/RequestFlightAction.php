<?php

namespace App\Actions\RequestFlight;

use App\Actions\RequestFlight\RequestFlightActionInput;
use App\Actions\RequestFlight\RequestFlightActionOutput;
use App\Events\FlightRequestedEvent;
use App\Exceptions\InvalidFlightDates;
use App\Models\Flight;
use App\Models\User;
use DateTime;

class RequestFlightAction
{
    public static function handle(RequestFlightActionInput $input): RequestFlightActionOutput
    {
        $user = User::findOrFail($input->userId);

        if ($input->departuneDate > $input->returnDate) {
            throw new InvalidFlightDates();
        }

        $flight = Flight::create([
            'user_id' => $user->id,
            'destination' => $input->destination,
            'departune_date' => $input->departuneDate->format(DateTime::ATOM),
            'return_date' => $input->returnDate->format(DateTime::ATOM)
        ]);

        FlightRequestedEvent::dispatch($flight);

        return new RequestFlightActionOutput(
            id: $flight->id,
            status: $flight->status,
            destination: $flight->destination,
            departuneDate: $flight->departune_date,
            returnDate: $flight->return_date,
        );
    }
}
