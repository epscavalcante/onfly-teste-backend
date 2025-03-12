<?php

namespace App\Actions\CancelFlight;

class CancelFlightActionInput
{
    public function __construct(
        public readonly int $flightId,
        public readonly int $userId,
    ) {}
}
