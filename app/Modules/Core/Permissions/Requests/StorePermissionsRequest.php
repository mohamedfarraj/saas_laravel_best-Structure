<?php

namespace App\Modules\Store\Permissions\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'display_name' => ['required', 'string', 'max:255'],
            'group' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'is_active.boolean' => 'The is_active field must be true or false.',
            'display_name.required' => 'The display name field is required.',
            'display_name.string' => 'The display name must be a string.',
            'display_name.max' => 'The display name may not be greater than 255 characters.',
            'group.string' => 'The group must be a string.',
        ];
    }
} 