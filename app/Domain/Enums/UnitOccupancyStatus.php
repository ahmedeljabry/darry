<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum UnitOccupancyStatus: string
{
    case VACANT = 'VACANT';
    case OCCUPIED = 'OCCUPIED';
    case MAINTENANCE = 'MAINTENANCE';
}

