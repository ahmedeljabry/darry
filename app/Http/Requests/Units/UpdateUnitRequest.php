<?php
declare(strict_types=1);

namespace App\Http\Requests\Units;

use App\Support\PropertyAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
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
        $parentRule = ['nullable','integer','exists:units,id'];
        if ($propertyId = PropertyAccess::currentId()) {
            $propertyRule[] = Rule::in([$propertyId]);
            $parentRule = ['nullable','integer', Rule::exists('units', 'id')->where('property_id', $propertyId)];
        }

        return [
            'property_id' => $propertyRule,
            'parent_id' => $parentRule,
            'name' => ['sometimes','required','string','max:255'],
            'unit_type' => ['sometimes','required','in:APARTMENT,ROOM,BED'],
            'capacity' => ['nullable','integer','min:1'],
            'rent_type' => ['sometimes','required','in:DAILY,MONTHLY,DAILY_OR_MONTHLY'],
            'rent_amount' => ['sometimes','required','numeric','min:0'],
            'status' => ['sometimes','required','in:ACTIVE,INACTIVE'],
        ];
    }

    public function attributes(): array
    {
        return (new StoreUnitRequest())->attributes();
    }
}
