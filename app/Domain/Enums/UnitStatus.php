<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum UnitStatus: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';

    public function label()
    {
        return match ($this) {
            self::ACTIVE => __('units.active'),
            self::INACTIVE => __('units.inactive'),
        };
    }
}

