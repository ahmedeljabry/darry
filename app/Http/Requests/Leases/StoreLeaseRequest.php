<?php
declare(strict_types=1);

namespace App\Http\Requests\Leases;

use App\Support\Rules\NoOverlapLeaseRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $unitId = $this->input('unit_id');
        return [
            'unit_id' => ['required','uuid','exists:units,id'],
            'tenant_id' => ['required','uuid','exists:tenants,id'],
            'start_date' => ['required','date','before_or_equal:end_date'],
            'end_date' => ['required','date','after_or_equal:start_date'],
            'billing_cycle' => ['required','in:DAILY,MONTHLY,YEARLY'],
            'rent_amount' => ['required','numeric','min:0'],
            'interval' => [new NoOverlapLeaseRule((string) $unitId)],
        ];
    }

    public function attributes(): array
    {
        return [
            'unit_id' => __('leases.unit'),
            'tenant_id' => __('leases.tenant'),
            'start_date' => __('leases.start_date'),
            'end_date' => __('leases.end_date'),
            'billing_cycle' => __('leases.billing_cycle'),
            'rent_amount' => __('leases.rent_amount'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'interval' => [
                'start_date' => $this->input('start_date'),
                'end_date' => $this->input('end_date'),
            ],
        ]);
    }
}

