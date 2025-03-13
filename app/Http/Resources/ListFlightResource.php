<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *  schema="ListFlightResource", *
 * 	@OA\Property(
 *      property="items",
 * 		type="array",
 *      @OA\Items(
 *          @OA\Property(
 *              property="id",
 *              type="int",
 *              example="1"
 *          ),
 *          @OA\Property(
 *              property="status",
 *              type="sting",
 *              example="APPROVED"
 *          ),
 *          @OA\Property(
 *              property="destination",
 *              type="sting",
 *              example="Belo Horizonte - MG"
 *          ),
 *          @OA\Property(
 *              property="departune_date",
 *              type="sting",
 *              example="2025-01-01T10:30:00"
 *          ),
 *          @OA\Property(
 *              property="return_date",
 *              type="sting",
 *              example="2025-01-02T19:30:00"
 *          ),
 *      )
 * 	),
 * )
 */
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
        return [
            'items' => array_map(
                callback: fn($item) => $item,
                array: $this->items
            )
        ];
    }
}
