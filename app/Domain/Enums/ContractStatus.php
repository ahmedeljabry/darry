<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum ContractStatus: string
{
    case ACTIVE = 'ACTIVE';
    case PENDING = 'PENDING';
    case TERMINATED = 'TERMINATED';
}

