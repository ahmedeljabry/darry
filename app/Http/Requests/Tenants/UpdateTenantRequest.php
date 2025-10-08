<?php
declare(strict_types=1);

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = $this->route('tenant')?->id ?? 'NULL';
        return [
            'tenant_type' => ['sometimes','required','in:PERSONAL,COMMERCIAL'],
            'full_name' => ['sometimes','required','string','max:255'],
            'national_id_or_cr' => ['nullable','string','max:190'],
            'work_or_study_place' => ['nullable','string','max:190'],
            'email' => [
                'nullable','email','max:255',
                'unique:tenants,email,'.$tenantId
            ],
            'phone' => ['nullable','string','max:50'],
            'phone2' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:500'],
            'relatives' => ['nullable','array','max:2'],
            'relatives.*.name' => ['nullable','string','max:255'],
            'relatives.*.id_no' => ['nullable','string','max:190'],
            'relatives.*.phone' => ['nullable','string','max:50'],
            'relatives.*.kinship' => ['nullable','string','max:190'],
        ];
    }
}
