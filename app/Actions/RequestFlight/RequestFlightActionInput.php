<?php

namespace App\Actions\RequestFlight;

use DateTime;

class RequestFlightActionInput
{
    public function __construct(
        public readonly int $userId,
        public readonly string $destination,
        public readonly DateTime $departuneDate,
        public readonly DateTime $returnDate,
    ) {}
}
