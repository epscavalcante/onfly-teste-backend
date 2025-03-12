<?php

namespace App\Exceptions;

use Exception;

class CannotAccessFlightDetailException extends Exception
{
    public function __construct(string $message = "Can't access flight details")
    {
        parent::__construct($message);
    }
}
