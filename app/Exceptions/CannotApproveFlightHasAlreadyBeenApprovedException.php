<?php

namespace App\Exceptions;

class CannotApproveFlightHasAlreadyBeenApprovedException extends CannotApproveFlightException
{
    public function __construct()
    {
        parent::__construct("It's not possible to approve a flight that already been approved");
    }
}
