<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum TenantType: string
{
    case PERSONAL = 'PERSONAL';
    case COMMERCIAL = 'COMMERCIAL';
}

