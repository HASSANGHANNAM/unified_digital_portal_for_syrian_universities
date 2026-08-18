<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetSanctionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],
            'status' => [
                'nullable',
                'string',
                'in:active,expired,revoked',
            ],
            'sanction_type_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],
            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'معرف الطالب مطلوب.',
            'student_id.exists' => 'الطالب غير موجود.',
            'status.in' => 'الحالة يجب أن تكون: active, expired, revoked.',
            'per_page.min' => 'عدد العناصر في الصفحة يجب أن يكون على الأقل 1.',
            'per_page.max' => 'عدد العناصر في الصفحة لا يتجاوز 100.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->missing('per_page')) {
            $this->merge(['per_page' => 15]);
        }
    }
}
