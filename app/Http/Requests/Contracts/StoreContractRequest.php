<?php
declare(strict_types=1);

namespace App\Http\Requests\Contracts;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'contract_no' => ['nullable','string','max:190','unique:contracts,contract_no'],
            'property_id' => ['required','integer','exists:properties,id'],
            'unit_id' => ['required','integer','exists:units,id'],
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
