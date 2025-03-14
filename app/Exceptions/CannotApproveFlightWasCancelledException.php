<?php

namespace App\Exceptions;

use Exception;

class CannotApproveFlightWasCancelledException extends CannotApproveFlightException
{
    public function __construct()
    {
        parent::__construct("It's not possible to approve flight thats cancelled");
    }
}
