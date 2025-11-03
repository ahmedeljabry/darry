<?php
declare(strict_types=1);

namespace App\Http\Requests\Contracts;

use App\Support\PropertyAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContractRequest extends FormRequest
{
    public function authorize(): bool { return true; }

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
            'contract_no' => ['nullable','string','max:190','unique:contracts,contract_no'],
            'property_id' => $propertyRule,
            'unit_id' => $unitRule,
            'tenant_id' => ['required','integer','exists:tenants,id'],
            'start_date' => ['required','date'],
            'duration_months' => ['required','integer','min:1','max:365'],
            'end_date' => ['nullable','date'],
            'payment_method' => ['nullable','in:CASH,BANK_TRANSFER,CHECK'],
            'payment_day' => ['nullable','integer','between:1,31'],
            'rent_amount' => ['nullable','numeric','min:0'],
        ];
    }
}
