<?php

namespace App\Actions\GetFlightDetail;

class GetFlightDetailActionInput
{
    public function __construct(
        public readonly int $flightId,
        public readonly int $userId,
    ) {}
}
