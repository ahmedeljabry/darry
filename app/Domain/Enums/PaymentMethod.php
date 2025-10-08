<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum PaymentMethod: string
{
    case CASH = 'CASH';
    case BANK_TRANSFER = 'BANK_TRANSFER';
    case CHECK = 'CHECK';
}

