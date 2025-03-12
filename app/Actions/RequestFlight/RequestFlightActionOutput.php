<?php

namespace App\Actions\RequestFlight;

class RequestFlightActionOutput
{
    public function __construct(
        public readonly string $id,
        public readonly string $status,
        public readonly string $departuneDate,
        public readonly string $returnDate,
    ) {}
}
