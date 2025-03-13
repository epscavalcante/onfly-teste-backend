<?php

namespace App\Http\Controllers;

use App\Actions\Login\LoginAction;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     @OA\RequestBody(ref="#/components/requestBodies/LoginRequest"),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/LoginResource"),
     *     ),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="422", description="Unprocessable Content"),
     * )
     */
    public function login(LoginRequest $request): LoginResource
    {
        $loginOutput = LoginAction::handle(
            email: $request->validated("email"),
            password: $request->validated('password')
        );

        return new LoginResource(
            accessToken: $loginOutput->token,
            tokenType: $loginOutput->tokenType,
            expiresIn: $loginOutput->expiresIn
        );
    }
}
