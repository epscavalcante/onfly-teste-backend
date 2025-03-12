<?php

namespace App\Actions\RequestFlight;

use App\Actions\RequestFlight\RequestFlightActionInput;
use App\Actions\RequestFlight\RequestFlightActionOutput;
use App\Events\FlightRequestedEvent;
use App\Exceptions\InvalidFlightDates;
use App\Models\Flight;
use App\Models\User;
use App\Notifications\FlightRequestedNotification;
use DateTime;

class RequestFlightAction
{
    public static function handle(RequestFlightActionInput $input): RequestFlightActionOutput
    {
        $user = User::findOrFail($input->userId);

        // validar se a data de ida é menor que a data de volta
        if ($input->departuneDate > $input->returnDate) {
            throw new InvalidFlightDates();
        }

        $flight = Flight::create([
            'user_id' => $user->id,
            'departune_date' => $input->departuneDate->format(DateTime::ATOM),
            'return_date' => $input->returnDate->format(DateTime::ATOM)
        ]);

        //$user->notify(new FlightRequestedNotification($flight));

        /**
         * 1 - podemos notificar o usuário diretamente aqui $user->notify(...)
         * 2 - utilizar a facade Event e configurar o listener para enviar a notification
         */

        FlightRequestedEvent::dispatch($flight);

        return new RequestFlightActionOutput(
            id: $flight->id,
            status: $flight->status,
            departuneDate: $flight->departune_date,
            returnDate: $flight->return_date,
        );
    }
}
