<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="RequestFlightResource",
 *     description="Request flight resource",
 *     @OA\Property(property="id", type="int", example="1"),
 *     @OA\Property(property="status", type="string", example="APPROVED"),
 *     @OA\Property(property="departune_date", type="string", example="2025-01-01T12:00:00"),
 *     @OA\Property(property="return_date", type="string", example="2025-01-01T12:00:00"),
 * )
 */
class RequestFlightResource extends JsonResource
{
    public function __construct(
        private readonly int $flightId,
        private readonly string $status,
        private readonly string $destination,
        private readonly string $departuneDate,
        private readonly string $returnDate,
    ) {}

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->flightId,
            'status' => $this->status,
            'destination' => $this->destination,
            'departune_date' => $this->departuneDate,
            'return_date' => $this->returnDate,
        ];
    }
}
