<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Invoice;
use App\Actions\Payments\RecordPaymentAction;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaymentsService
{
    public function __construct(private readonly RecordPaymentAction $recordPayment)
    {
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Payment::query()->with('invoice')->latest('paid_at')->paginate($perPage);
    }

    public function store(Invoice $invoice, array $data): Payment
    {
        return $this->recordPayment->execute($invoice, $data);
    }
}
