<?php

namespace App\Exceptions;

use Exception;

class CannotCancelFlightException extends Exception
{
    public function __construct(string $message = "It's not possible to cancel flight")
    {
        parent::__construct($message);
    }
}
