<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestsInStudentCollegeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'is_available' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'اسم نوع الطلب يجب أن يكون نصياً.',
            'name.max' => 'ألا يزيد طول اسم نوع الطلب عن 255 حرفاً.',
            'is_available.boolean' => 'حقل is_available يجب أن يكون صحيحاً (true/false) أو (1/0).',
        ];
    }
}
