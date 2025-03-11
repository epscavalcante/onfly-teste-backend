<?php

namespace App\Http\Controllers;

use App\Actions\Login\LoginAction;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $loginOutput = LoginAction::handle(
            email: $request->validated("email"),
            password: $request->validated('password')
        );

        return response()->json([
            'access_token' => $loginOutput->token,
            'token_type' => $loginOutput->tokenType,
            'expires_in' => $loginOutput->expiresIn
        ]);
    }
}
