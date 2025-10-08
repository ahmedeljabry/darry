<?php
declare(strict_types=1);

namespace App\Http\Requests\Units;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable','uuid','exists:units,id'],
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

