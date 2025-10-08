<?php
declare(strict_types=1);

namespace App\Http\Requests\Payments;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required','numeric','min:0.01'],
            'method' => ['required','in:CASH,BANK_TRANSFER,CHEQUE'],
            'paid_at' => ['required','date'],
        ];
    }
}

