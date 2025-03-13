<?php

namespace App\Http\Controllers;

use App\Actions\ApproveFlight\ApproveFlightAction;
use App\Actions\ApproveFlight\ApproveFlightActionInput;
use App\Actions\CancelFlight\CancelFlightAction;
use App\Actions\CancelFlight\CancelFlightActionInput;
use App\Actions\GetFlightDetail\GetFlightDetailAction;
use App\Actions\GetFlightDetail\GetFlightDetailActionInput;
use App\Actions\ListFLights\ListFlightsAction;
use App\Actions\ListFLights\ListFlightsActionInput;
use App\Actions\RequestFlight\RequestFlightAction;
use App\Actions\RequestFlight\RequestFlightActionInput;
use App\Http\Requests\ListFlightRequest;
use App\Http\Requests\StoreFlightRequest;
use App\Http\Resources\GetFlightDetailResource;
use App\Http\Resources\ListFlightResource;
use App\Http\Resources\RequestFlightResource;
use DateTime;
use Illuminate\Http\Response;
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

    public function approve(int $flightId): Response
    {
        $approveFlightInput = new ApproveFlightActionInput(
            flightId: $flightId,
            userApproverId: Auth::id()
        );

        ApproveFlightAction::handle($approveFlightInput);

        return response()->noContent();
    }


    public function cancel(int $flightId): Response
    {
        $cancelFlightInput = new CancelFlightActionInput(
            flightId: $flightId,
            userId: Auth::id()
        );

        CancelFlightAction::handle($cancelFlightInput);

        return response()->noContent();
    }

    public function detail(int $flightId): GetFlightDetailResource
    {
        $flightDetailInput = new GetFlightDetailActionInput(
            flightId: $flightId,
            userId: Auth::id()
        );

        $flightDetailOutput = GetFlightDetailAction::handle($flightDetailInput);

        return new GetFlightDetailResource(
            flightId: $flightDetailOutput->flightId,
            status: $flightDetailOutput->status,
            destination: $flightDetailOutput->destination,
            returnDate: $flightDetailOutput->returnDate,
            departuneDate: $flightDetailOutput->departuneDate,
            createdAt: $flightDetailOutput->createdAt,
            updatedAt: $flightDetailOutput->updatedAt,
        );
    }

    public function list(ListFlightRequest $request): ListFlightResource
    {
        $input = new ListFlightsActionInput(
            userId: Auth::id(),
            status: $request->validated('status', null),
            startDate: $request->validated('start_date', null),
            endDate: $request->validated('end_date', null),
        );
        $output = ListFlightsAction::handle($input);

        return new ListFlightResource($output->items);
    }
}
