<?php
declare(strict_types=1);

namespace App\Http\Requests\Expenses;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'title' => ['sometimes','required','string','max:255'],
            'date' => ['sometimes','required','date'],
            'amount' => ['sometimes','required','numeric'],
            'receipt_no' => ['nullable','string','max:190'],
            'category' => ['nullable','string','max:190'],
            'property_id' => ['sometimes','required','uuid','exists:properties,id'],
            'unit_id' => ['nullable','uuid','exists:units,id'],
        ];
    }
}

