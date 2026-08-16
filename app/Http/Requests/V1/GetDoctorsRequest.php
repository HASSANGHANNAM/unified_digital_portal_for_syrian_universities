<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetDoctorsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],

            'department_id' => [
                'nullable',
                'integer',
                'exists:departments,id',
            ],
            'college_id' => [
                'nullable',
                'integer',
                'exists:colleges,id',
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
            'department_id.exists' => 'القسم المحدد غير موجود.',
            'college_id.exists'   => 'الكلية المحددة غير موجودة.',
            'per_page.min'        => 'عدد العناصر في الصفحة يجب أن يكون على الأقل 1.',
            'per_page.max'        => 'عدد العناصر في الصفحة لا يتجاوز 100.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->missing('per_page')) {
            $this->merge(['per_page' => 15]);
        }
    }
}
