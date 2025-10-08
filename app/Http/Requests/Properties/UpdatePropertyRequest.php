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
            'country' => ['nullable','string','max:190'],
            'state' => ['nullable','string','max:190'],
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
            'facilities.*' => ['uuid'],
        ];
    }
}
