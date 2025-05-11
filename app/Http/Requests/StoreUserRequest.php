<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Set to false if you want to block unauthorized access
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'age' => ['nullable', 'integer', 'min:0'],
            //'password' => ['required', 'string', 'min:6'],
        ];
    }
}
