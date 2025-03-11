<?php

namespace App\Actions\Login;

use App\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Auth;

class LoginAction
{
    public static function handle(string $email, string $password): LoginActionOutput
    {
        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        if (! $token = Auth::attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        return new LoginActionOutput(
            token: $token,
            tokenType: 'Bearer',
            expiresIn: Auth::factory()->getTTL() * 60
        );
    }
}
