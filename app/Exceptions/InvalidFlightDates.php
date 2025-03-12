<?php

namespace App\Exceptions;

use Exception;

class InvalidFlightDates extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid flight dates');
    }
}
