<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     title="LoginResource",
 *     description="Login resource",
 *     @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL2xvZ2luIiwiaWF0IjoxNzQxODcyMTAxLCJleHAiOjE3NDE4NzU3MDEsIm5iZiI6MTc0MTg3MjEwMSwianRpIjoiNmNZT0lsYkV6aEFURER4VyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.FlFGl3okLHWb4HTjRKBimhJaoobDpQ9jJz21F_mpMp8"),
 *     @OA\Property(property="token_type", type="string", example="Bearer"),
 *     @OA\Property(property="expires_in", type="int", example="3600"),
 * )
 */
class LoginResource extends JsonResource
{
    public function __construct(
        private readonly string $accessToken,
        private readonly string $tokenType,
        private readonly int $expiresIn,
    ) {}

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->accessToken,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
        ];
    }
}
