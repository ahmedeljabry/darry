<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum BillingCycle: string
{
    case DAILY = 'DAILY';
    case MONTHLY = 'MONTHLY';
    case YEARLY = 'YEARLY';
}

