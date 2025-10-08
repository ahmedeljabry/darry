<?php
declare(strict_types=1);

namespace App\Http\Requests\Units;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable','uuid','exists:units,id'],
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

