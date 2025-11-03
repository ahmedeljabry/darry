<?php
declare(strict_types=1);

namespace App\Http\Requests\Units;

use App\Support\PropertyAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUnitRequest extends FormRequest
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
        $parentRule = ['nullable','integer','exists:units,id'];
        if ($propertyId = PropertyAccess::currentId()) {
            $propertyRule[] = Rule::in([$propertyId]);
            $parentRule = ['nullable','integer', Rule::exists('units', 'id')->where('property_id', $propertyId)];
        }

        return [
            'property_id' => $propertyRule,
            'parent_id' => $parentRule,
            'name' => ['required','string','max:255'],
            'unit_type' => ['required','in:APARTMENT,ROOM,BED'],
            'capacity' => ['nullable','integer','min:1'],
            'rent_type' => ['required','in:DAILY,MONTHLY,DAILY_OR_MONTHLY'],
            'rent_amount' => ['required','numeric','min:0'],
            'status' => ['required','in:ACTIVE,INACTIVE'],
        ];
    }

    public function attributes(): array
    {
        return [
            'property_id' => __('units.property'),
            'parent_id' => __('units.parent_unit'),
            'name' => __('units.name'),
            'unit_type' => __('units.unit_type'),
            'capacity' => __('units.capacity'),
            'rent_type' => __('units.rent_type'),
            'rent_amount' => __('units.rent_amount'),
            'status' => __('units.status'),
        ];
    }
}
