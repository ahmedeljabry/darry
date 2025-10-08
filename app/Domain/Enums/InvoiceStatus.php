<?php
declare(strict_types=1);

namespace App\Domain\Enums;

enum InvoiceStatus: string
{
    case DRAFT = 'DRAFT';
    case ISSUED = 'ISSUED';
    case PAID = 'PAID';
    case OVERDUE = 'OVERDUE';
    case VOID = 'VOID';
}

