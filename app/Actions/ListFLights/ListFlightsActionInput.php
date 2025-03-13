<?php

namespace App\Actions\ListFLights;

class ListFlightsActionInput
{
    public function __construct(
        public readonly ?int $userId = null,
        public readonly ?string $status = null,
        public readonly ?string $startDate = null,
        public readonly ?string $endDate = null,
    ) {}
}
