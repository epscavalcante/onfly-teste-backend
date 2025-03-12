<?php

namespace App\Http\Controllers;

use App\Actions\RequestFlight\RequestFlightAction;
use App\Actions\RequestFlight\RequestFlightActionInput;
use App\Http\Requests\StoreFlightRequest;
use App\Http\Resources\RequestFlightResource;
use DateTime;
use Illuminate\Support\Facades\Auth;

class FlightController extends Controller
{
    public function store(StoreFlightRequest $request): RequestFlightResource
    {
        $requestFlightInput = new RequestFlightActionInput(
            userId: Auth::id(),
            destination: $request->validated('destination'),
            departuneDate: new DateTime($request->validated('departune_date')),
            returnDate: new DateTime($request->validated('return_date')),
        );

        $requestFlightOutput = RequestFlightAction::handle($requestFlightInput);

        return new RequestFlightResource(
            flightId: $requestFlightOutput->id,
            status: $requestFlightOutput->status,
            destination: $requestFlightOutput->destination,
            departuneDate: $requestFlightOutput->departuneDate,
            returnDate: $requestFlightOutput->returnDate
        );
    }
}
