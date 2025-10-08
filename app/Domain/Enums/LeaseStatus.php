<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum LeaseStatus: string
{
    case PENDING = 'PENDING';
    case ACTIVE = 'ACTIVE';
    case TERMINATED = 'TERMINATED';
    case EXPIRED = 'EXPIRED';
}

