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

/**
 * @OA\PathItem(path="/api/flights")
 */
class FlightController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/flights",
     *     tags={"Flights"},
     *     summary="Request flight",
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(ref="#/components/requestBodies/StoreFlightRequest"),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/RequestFlightResource"),
     *     ),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Unprocessable Content"),
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/flights/{id}/approve",
     *     tags={"Flights"},
     *     summary="Approve flight",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         description="id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="id", value="1", summary="Some int value."),
     *     ),
     *     @OA\Response(response="204", description="No Content"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Forbidden",),
     *     @OA\Response(response="422", description="Unprocessable Content"),
     * )
     */
    public function approve(int $flightId): Response
    {
        $approveFlightInput = new ApproveFlightActionInput(
            flightId: $flightId,
            userApproverId: Auth::id()
        );

        ApproveFlightAction::handle($approveFlightInput);

        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/api/flights/{id}/cancel",
     *     tags={"Flights"},
     *     summary="Cancel flight",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         description="id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="id", value="1", summary="Some int value."),
     *     ),
     *     @OA\Response(response="204", description="No Content"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Forbidden",),
     *     @OA\Response(response="422", description="Unprocessable Content"),
     * )
     */
    public function cancel(int $flightId): Response
    {
        $cancelFlightInput = new CancelFlightActionInput(
            flightId: $flightId,
            userId: Auth::id()
        );

        CancelFlightAction::handle($cancelFlightInput);

        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/api/flights/{id}",
     *     tags={"Flights"},
     *     summary="Flight detail",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         description="id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="id", value="1", summary="Some int value."),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/GetFlightDetailResource"),
     *     ),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Forbidden",),
     *     @OA\Response(response="404", description="Not Found"),
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/flights",
     *     tags={"Flights"},
     *     summary="List of flights",
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         in="query",
     *         name="status",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="requested", value="REQUESTED", summary="Example REQUESTED status"),
     *         @OA\Examples(example="approved", value="APPROVED", summary="Example APPROVED status"),
     *         @OA\Examples(example="cancelled", value="CANCELLED", summary="Example CANCELLED status")
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="start_date",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="2025-01-01", value="2025-01-01", summary="Example 2025-01-01 date"),
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="end_date",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="2025-12-31", value="2025-12-31", summary="Example 2025-12-31 date"),
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/ListFlightResource"),
     *     ),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Forbidden",),
     *     @OA\Response(response="422", description="Unprocessable Content"),
     * )
     */
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
