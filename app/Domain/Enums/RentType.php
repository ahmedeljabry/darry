<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum RentType: string
{
    case DAILY = 'DAILY';
    case MONTHLY = 'MONTHLY';
    case DAILY_OR_MONTHLY = 'DAILY_OR_MONTHLY';
}

