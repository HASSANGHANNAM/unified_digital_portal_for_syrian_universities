<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetStudentSuggestions extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'status' => [
                'nullable',
                'string',
                Rule::in(['قيد المراجعة', 'تم القبول', 'تم الرفض']),
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
            'content.string'   => 'المحتوى يجب أن يكون نصاً.',
            'content.max'      => 'المحتوى لا يمكن أن يتجاوز 1000 حرف.',

            'status.string' => 'حالة الاقتراح يجب أن تكون نصاً.',
            'status.in'     => 'الحالة يجب أن تكون واحدة من: قيد المراجعة، تم القبول، تم الرفض.',

            'per_page.integer' => 'عدد العناصر في الصفحة يجب أن يكون رقمًا صحيحًا.',
            'per_page.min'     => 'عدد العناصر في الصفحة يجب أن يكون على الأقل 1.',
            'per_page.max'     => 'عدد العناصر في الصفحة لا يمكن أن يتجاوز 100.',

            'page.integer' => 'رقم الصفحة يجب أن يكون رقمًا صحيحًا.',
            'page.min'     => 'رقم الصفحة يجب أن يكون على الأقل 1.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->missing('per_page')) {
            $this->merge(['per_page' => 15]);
        }
    }
}
