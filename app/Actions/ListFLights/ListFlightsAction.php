<?php

namespace App\Actions\ListFLights;

use App\Models\Flight;
use DateTime;
use Illuminate\Database\Eloquent\Builder;

class ListFlightsAction
{
    public static function handle(ListFlightsActionInput $input): ListFlightsActionOutput
    {
        $flightsQuery = Flight::query();

        $flightsQuery->when(
            value: $input->userId,
            callback: fn(Builder $query) => $query->ofUser($input->userId)
        );

        $flightsQuery->when(
            value: $input->status,
            callback: fn(Builder $query) => $query->ofStatus($input->status)
        );

        $flightsQuery->when(
            value: $input->startDate || $input->endDate,
            callback: fn(Builder $query) => $query->ofPeriod(
                $input->startDate ? new DateTime($input->startDate) : null,
                $input->endDate ? new DateTime($input->endDate) :  null
            )
        );

        $items = $flightsQuery->get();

        return new ListFlightsActionOutput(
            items: array_map(
                callback: function (Flight $flight) {
                    return [
                        'id' => $flight->id,
                        'status' => $flight->status,
                        'destination' => $flight->destination,
                        'departune_date' => $flight->departune_date,
                        'return_date' => $flight->return_date,
                    ];
                },
                array: $items->all()
            )
        );
    }
}
