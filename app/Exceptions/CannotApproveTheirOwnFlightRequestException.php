<?php

namespace App\Exceptions;

class CannotApproveTheirOwnFlightRequestException extends CannotApproveFlightException
{
    public function __construct()
    {
        parent::__construct('User cannot approve their own flight request.');
    }
}
