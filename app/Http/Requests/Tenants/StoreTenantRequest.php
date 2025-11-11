<?php
declare(strict_types=1);

namespace App\Http\Requests\Tenants;

use App\Support\PropertyAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTenantRequest extends FormRequest
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
        $propertyId = $this->input('property_id') ?? PropertyAccess::currentId();
        $propertyRule = ['required','integer','exists:properties,id'];
        if ($forcedPropertyId = PropertyAccess::currentId()) {
            $propertyRule[] = Rule::in([$forcedPropertyId]);
        }

        $emailRule = ['nullable','email','max:255'];
        $emailRule[] = Rule::unique('tenants', 'email')
            ->where(static function ($query) use ($propertyId) {
                if ($propertyId) {
                    $query->where('property_id', $propertyId);
                }

                return $query;
            });

        return [
            'property_id' => $propertyRule,
            'tenant_type' => ['required','in:PERSONAL,COMMERCIAL'],
            'full_name' => ['required','string','max:255'],
            'national_id_or_cr' => ['nullable','string','max:190'],
            'work_or_study_place' => ['nullable','string','max:190'],
            'email' => $emailRule,
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
