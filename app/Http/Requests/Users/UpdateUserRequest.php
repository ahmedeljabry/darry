<?php
declare(strict_types=1);

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user')?->id ?? $this->route('users');
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email,' . $id],
            'phone' => ['nullable','string','max:50'],
            'status' => ['required','in:ACTIVE,INACTIVE'],
            'role' => ['required','exists:roles,name'],
            'password' => ['nullable','confirmed','min:8'],
        ];
    }
}

