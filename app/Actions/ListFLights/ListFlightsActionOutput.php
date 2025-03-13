<?php

namespace App\Actions\ListFLights;

class ListFlightsActionOutput
{
    public function __construct(public readonly array $items) {}
}
