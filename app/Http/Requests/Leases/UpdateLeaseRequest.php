<?php
declare(strict_types=1);

namespace App\Http\Requests\Leases;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes','required','in:PENDING,ACTIVE,TERMINATED,EXPIRED'],
        ];
    }
}

