<?php
declare(strict_types=1);

namespace App\Http\Requests\Properties;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'country_id' => ['nullable','integer','exists:countries,id'],
            'country' => ['nullable','string','max:190'],
            'governorate_id' => ['nullable','integer','exists:governorates,id'],
            'state' => ['nullable','string','max:190'],
            'state_id' => ['nullable','integer','exists:states,id'],
            'governorate' => ['nullable','string','max:190'],
            'city' => ['nullable','string','max:190'],
            'coordinates' => ['nullable','string','max:255'],
            'area_sqm' => ['nullable','integer'],
            'use_type' => ['nullable','string'],
            'thumbnail' => ['nullable','image'],
            'images' => ['nullable','array'],
            'images.*' => ['nullable','image'],
            'delete_thumbnail' => ['nullable','string'],
            'delete_images' => ['nullable','array'],
            'delete_images.*' => ['nullable','string'],
            'facilities' => ['nullable','array'],
            'facilities.*' => ['integer','exists:facilities,id'],
        ];
    }
}
