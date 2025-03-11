<?php

namespace App\Enums;

enum FlightStatusEnum: string
{
    case REQUESTED = 'REQUESTED';

    case APPROVED = 'APPROVED';

    case CANCELED = 'CANCELED';
}
