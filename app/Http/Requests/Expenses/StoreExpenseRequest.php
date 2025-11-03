<?php
declare(strict_types=1);

namespace App\Http\Requests\Expenses;

use App\Support\PropertyAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        if ($propertyId = PropertyAccess::currentId()) {
            $this->merge(['property_id' => $propertyId]);
        }
    }

    public function rules(): array
    {
        $propertyRule = ['required','integer','exists:properties,id'];
        $unitRule = ['nullable','integer','exists:units,id'];
        if ($propertyId = PropertyAccess::currentId()) {
            $propertyRule[] = Rule::in([$propertyId]);
            $unitRule = ['nullable','integer', Rule::exists('units', 'id')->where('property_id', $propertyId)];
        }

        return [
            'title' => ['required','string','max:255'],
            'date' => ['required','date'],
            'amount' => ['required','numeric'],
            'receipt_no' => ['nullable','string','max:190'],
            'category' => ['nullable','string','max:190'],
            'property_id' => $propertyRule,
            'unit_id' => $unitRule,
        ];
    }
}
