<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum UnitType: string
{
    case APARTMENT = 'APARTMENT';
    case ROOM = 'ROOM';
    case BED = 'BED';
}

