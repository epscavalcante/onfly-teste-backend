<?php

namespace App\Exceptions;

class CannotCancelFlightHasAlreadyBeenCancelledException extends CannotCancelFlightException
{
    public function __construct()
    {
        parent::__construct("It's not possible to cancel a flight that already been cancelled");
    }
}
