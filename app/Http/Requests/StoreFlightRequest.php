<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\RequestBody(
 *     request="StoreFlightRequest",
 *     description="Body to request a flight",
 *     @OA\MediaType(
 *         mediaType="application/json",
 *         @OA\Schema(
 *             example={"destination": "Belo Horizonte - MG", "departune_date": "2025-01-01T23:00:00", "return_date": "2025-01-02T13:00:00"},
 *             @OA\Property(
 *                 property="destination",
 *                 type="string",
 *                 example="Belo Horizonte - MG"
 *             ),
 *              @OA\Property(
 *                 property="departune_date",
 *                 type="date",
 *                 example="2025-01-01T23:00:00"
 *             ),
 *             @OA\Property(
 *                 property="return_date",
 *                 type="date",
 *                 example="2025-01-01T23:00:00"
 *             ),
 *         )
 *     )
 * ),
 */
class StoreFlightRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'destination' => 'required|string',
            'departune_date' => 'required|date',
            'return_date' => 'required|date',
        ];
    }
}
