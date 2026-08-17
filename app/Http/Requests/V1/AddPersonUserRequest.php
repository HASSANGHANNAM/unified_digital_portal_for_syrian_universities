<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

abstract class AddPersonUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'person_id' => ['required', 'integer', 'unique:persons,id'],
            'password' => ['required', 'string', 'min:8'],
            'national_id' => ['required', 'string', 'max:50', 'unique:persons,national_id'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'national_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'اسم المستخدم مطلوب.',
            'username.unique' => 'اسم المستخدم موجود مسبقاً.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'البريد الإلكتروني موجود مسبقاً.',
            'person_id.required' => 'معرف الشخص مطلوب.',
            'person_id.unique' => 'هذا الشخص موجود بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل.',
            'national_id.required' => 'الرقم الوطني مطلوب.',
            'national_id.unique' => 'الرقم الوطني موجود مسبقاً.',
            'full_name.required' => 'الاسم الكامل مطلوب.',
            'birth_date.date' => 'تاريخ الميلاد يجب أن يكون تاريخاً صحيحاً.',
        ];
    }
}
