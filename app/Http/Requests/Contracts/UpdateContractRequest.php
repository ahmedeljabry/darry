<?php
declare(strict_types=1);

namespace App\Http\Requests\Contracts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContractRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('contract')?->id ?? 'NULL';
        return [
            'contract_no' => ['nullable','string','max:190','unique:contracts,contract_no,'.$id],
            'property_id' => ['sometimes','required','uuid','exists:properties,id'],
            'unit_id' => ['sometimes','required','uuid','exists:units,id'],
            'tenant_id' => ['sometimes','required','uuid','exists:tenants,id'],
            'start_date' => ['sometimes','required','date'],
            'duration_months' => ['sometimes','required','integer','min:1','max:120'],
            'end_date' => ['nullable','date'],
            'payment_method' => ['nullable','in:CASH,BANK_TRANSFER,CHECK'],
            'payment_day' => ['nullable','integer','between:1,31'],
            'rent_amount' => ['nullable','numeric','min:0'],
        ];
    }
}

