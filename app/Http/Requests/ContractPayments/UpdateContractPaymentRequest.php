<?php
declare(strict_types=1);

namespace App\Http\Requests\ContractPayments;

use App\Support\PropertyAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContractPaymentRequest extends FormRequest
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
        $propertyRule = ['sometimes','required','integer','exists:properties,id'];
        $unitRule = ['sometimes','required','integer','exists:units,id'];
        if ($propertyId = PropertyAccess::currentId()) {
            $propertyRule[] = Rule::in([$propertyId]);
            $unitRule = ['sometimes','required','integer', Rule::exists('units', 'id')->where('property_id', $propertyId)];
        }

        return [
            'property_id' => $propertyRule,
            'unit_id' => $unitRule,
            'tenant_id' => ['sometimes','required','integer','exists:tenants,id'],
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
