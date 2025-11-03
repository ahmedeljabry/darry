<?php
declare(strict_types=1);

namespace App\Http\Requests\ContractPayments;

use App\Support\PropertyAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContractPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($propertyId = PropertyAccess::currentId()) {
            $this->merge(['property_id' => $propertyId]);
        }
    }

    public function rules(): array
    {
        $propertyRule = ['required','integer','exists:properties,id'];
        $unitRule = ['required','integer','exists:units,id'];
        if ($propertyId = PropertyAccess::currentId()) {
            $propertyRule[] = Rule::in([$propertyId]);
            $unitRule = ['required','integer', Rule::exists('units', 'id')->where('property_id', $propertyId)];
        }

        return [
            'property_id' => $propertyRule,
            'unit_id' => $unitRule,
            'tenant_id' => ['required','integer','exists:tenants,id'],
            'period_month' => ['required','integer','between:1,12'],
            'period_year' => ['required','integer','between:2000,2100'],
            'amount_due' => ['required','numeric','min:0'],
            'amount_paid' => ['nullable','numeric','min:0'],
            'due_date' => ['required','date'],
            'paid_at' => ['nullable','date'],
            'method' => ['required','in:CASH,BANK_TRANSFER,CHECK'],
            'details' => ['nullable','string','max:5000'],
        ];
    }
}
