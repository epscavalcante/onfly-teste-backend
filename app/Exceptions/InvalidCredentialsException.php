<?php

namespace App\Exceptions;

use Illuminate\Validation\UnauthorizedException;

class InvalidCredentialsException extends UnauthorizedException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Invalid credentials'
        );
    }
}
