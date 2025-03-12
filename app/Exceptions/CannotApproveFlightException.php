<?php

namespace App\Exceptions;

use Exception;

class CannotApproveFlightException extends Exception
{
    public function __construct(string $message = "It's not possible to approve flight")
    {
        parent::__construct($message);
    }
}
