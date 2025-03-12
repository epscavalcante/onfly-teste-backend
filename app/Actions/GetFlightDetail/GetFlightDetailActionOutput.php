<?php

namespace App\Actions\GetFlightDetail;

class GetFlightDetailActionOutput
{
    public function __construct(
        public readonly int $flightId,
        public readonly string $status,
        public readonly string $destination,
        public readonly string $departuneDate,
        public readonly string $returnDate,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {}
}
