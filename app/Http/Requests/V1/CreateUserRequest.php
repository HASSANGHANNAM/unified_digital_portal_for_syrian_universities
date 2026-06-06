<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role_name' => [
                'required',
                'string',
                Rule::exists('roles', 'name'),
                Rule::notIn(['admin', 'student']),
            ],
            'person_id' => ['required', 'integer', 'exists:persons,id', 'unique:users,person_id'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
        ];
    }
}
