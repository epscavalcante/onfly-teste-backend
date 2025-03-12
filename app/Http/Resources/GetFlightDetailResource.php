<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetFlightDetailResource extends JsonResource
{
    public function __construct(
        private readonly int $flightId,
        private readonly string $status,
        private readonly string $destination,
        private readonly string $departuneDate,
        private readonly string $returnDate,
        private readonly string $createdAt,
        private readonly string $updatedAt,
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
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
