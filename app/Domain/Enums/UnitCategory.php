<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum UnitCategory: string
{
    case SHOP = 'SHOP';
    case FLAT = 'FLAT';
    case SHOWROOM = 'SHOWROOM';
    case WAREHOUSE = 'WAREHOUSE';
    case OTHER = 'OTHER';
}

