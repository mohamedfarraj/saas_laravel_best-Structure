<?php

namespace App\Modules\Tenant\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:tenant.users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
} 