<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetCourseStudentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'nullable',
                'string',
                Rule::in(['pass', 'fail', 'helped pass']),
            ],
            'academic_year' => [
                'nullable',
                'string',
                'max:20',
            ],
            'semester' => [
                'nullable',
                'integer',
                'min:1',
                'max:12',
            ],
            'student_id_number' => [
                'nullable',
                'string',
                'max:50',
            ],
            'student_name' => [
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
            'status.in' => 'الحالة المحددة غير صالحة. القيم المسموحة: pass, fail, helped pass.',
            'semester.min' => 'رقم الفصل يجب أن يكون على الأقل 1.',
            'semester.max' => 'رقم الفصل يجب أن لا يتجاوز 12.',
            'per_page.min' => 'عدد العناصر في الصفحة يجب أن يكون على الأقل 1.',
            'per_page.max' => 'عدد العناصر في الصفحة لا يمكن أن يتجاوز 100.',
        ];
    }
    protected function prepareForValidation(): void
    {
        if ($this->missing('per_page')) {
            $this->merge(['per_page' => 15]);
        }
    }
}
