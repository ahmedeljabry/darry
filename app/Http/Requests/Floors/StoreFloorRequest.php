<?php
declare(strict_types=1);

namespace App\Http\Requests\Floors;

use Illuminate\Foundation\Http\FormRequest;

class StoreFloorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ar' => ['required','string','max:255'],
            'description_en' => ['nullable','string','max:255'],
            'sort_order' => ['nullable','integer','min:0','max:9999'],
        ];
    }
}

