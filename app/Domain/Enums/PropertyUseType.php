<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum PropertyUseType: string
{
    case RESIDENTIAL = 'RESIDENTIAL';
    case COMMERCIAL = 'COMMERCIAL';
    case INDUSTRIAL = 'INDUSTRIAL';
    case AGRICULTURAL = 'AGRICULTURAL';
    case MIXED = 'MIXED';
}

