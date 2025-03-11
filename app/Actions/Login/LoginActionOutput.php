<?php

namespace App\Actions\Login;

class LoginActionOutput
{
    public function __construct(
        public readonly string $token,
        public readonly string $tokenType,
        public readonly int $expiresIn,
    ) {}
}
