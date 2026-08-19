<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'person_id' => ['required', 'integer', 'unique:persons,id']
        ];
        $rules['role'] = [
            'required',
            'string',
            'max:255',
            Rule::exists('roles', 'name'),
        ];
        $rules['department_id'] = [
            'required',
            'integer',
            'exists:departments,id',
        ];

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'person_id.unique' => 'هذا الشخص موجود بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
        ];
        $messages['role.required'] = 'الصلاحية (الدور) مطلوبة.';
        $messages['role.exists'] = 'الصلاحية المحددة غير موجودة في النظام.';
        $messages['department_id.required'] = 'معرف القسم مطلوب.';
        $messages['department_id.exists'] = 'القسم غير موجود.';

        return $messages;
    }
}
