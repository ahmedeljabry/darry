<?php
declare(strict_types=1);

namespace App\Http\Requests\Expenses;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'date' => ['required','date'],
            'amount' => ['required','numeric'],
            'receipt_no' => ['nullable','string','max:190'],
            'category' => ['nullable','string','max:190'],
            'property_id' => ['required','uuid','exists:properties,id'],
            'unit_id' => ['nullable','uuid','exists:units,id'],
        ];
    }
}

