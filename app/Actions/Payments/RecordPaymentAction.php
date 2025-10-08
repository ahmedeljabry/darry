<?php
declare(strict_types=1);

namespace App\Actions\Payments;

use App\Domain\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

final class RecordPaymentAction
{
    public function execute(Invoice $invoice, array $data): Payment
    {
        return DB::transaction(function () use ($invoice, $data) {
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $data['amount'],
                'method' => $data['method'],
                'paid_at' => $data['paid_at'],
            ]);

            $paid = $invoice->payments()->sum('amount');
            $due = max(0, (float) $invoice->total - (float) $paid);
            $invoice->update(['status' => $due <= 0 ? InvoiceStatus::PAID->value : $invoice->status]);

            return $payment;
        });
    }
}
