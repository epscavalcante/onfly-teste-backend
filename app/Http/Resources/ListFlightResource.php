<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListFlightResource extends ResourceCollection
{
    public function __construct(
        private readonly array $items
    ) {}

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_map(
            callback: fn ($item) => $item,
            array: $this->items
        );
    }
}
