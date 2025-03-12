<?php

namespace App\Actions\ApproveFlight;

class ApproveFlightActionInput
{
    public function __construct(
        public readonly int $flightId,
        public readonly int $userApproverId,
    ) {}
}
