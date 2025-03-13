<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\RequestBody(
 *     request="LoginRequest",
 *     description="Body to login",
 *     @OA\MediaType(
 *         mediaType="application/json",
 *         @OA\Schema(
 *             example={"email": "user1@example.com", "password": "password"},
 *             @OA\Property(
 *                 property="email",
 *                 type="string",
 *                 example="john.doe@email.com"
 *             ),
 *              @OA\Property(
 *                 property="password",
 *                 type="string",
 *                 example="password"
 *             ),
 *         )
 *     )
 * ),
 */
class LoginRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }
}
