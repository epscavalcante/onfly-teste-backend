<?php

namespace App\Exceptions;

use Exception;

class CannotApproveFlightException extends Exception
{
    public function __construct()
    {
        parent::__construct("It's not possible to approve flight");
    }
}
