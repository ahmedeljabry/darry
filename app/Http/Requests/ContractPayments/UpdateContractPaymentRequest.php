<?php
declare(strict_types=1);

namespace App\Http\Requests\ContractPayments;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContractPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'property_id' => ['sometimes','required','uuid','exists:properties,id'],
            'unit_id' => ['sometimes','required','uuid','exists:units,id'],
            'tenant_id' => ['sometimes','required','uuid','exists:tenants,id'],
            'period_month' => ['sometimes','required','integer','between:1,12'],
            'period_year' => ['sometimes','required','integer','between:2000,2100'],
            'amount_due' => ['sometimes','required','numeric','min:0'],
            'amount_paid' => ['nullable','numeric','min:0'],
            'due_date' => ['sometimes','required','date'],
            'paid_at' => ['nullable','date'],
            'method' => ['sometimes','required','in:CASH,BANK_TRANSFER,CHECK'],
            'details' => ['nullable','string','max:5000'],
        ];
    }
}

