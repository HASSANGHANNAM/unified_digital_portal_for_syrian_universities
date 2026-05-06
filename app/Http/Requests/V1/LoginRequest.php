<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'email',
            'password' => 'required|string|min:6',
            'username' => 'required|string',
            'new_password'=>'string|min:6',
            'email_verified'=>'nullable',
            'status'=>'string',
            'last_login'=>'nullable',
            'person_id'=>'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'username is required',
            'email.email' => 'The email format is incorrect',
            'password.required' => 'Password is required',
        ];
    }
}
