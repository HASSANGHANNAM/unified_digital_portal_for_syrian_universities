<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetDoctorCoursesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'college_id'    => ['required', 'exists:colleges,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'course_name'   => ['nullable', 'string', 'max:255'],
            'per_page'      => ['nullable', 'integer', 'min:1', 'max:100'],
            'page'          => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'college_id.required' => 'معرف الكلية مطلوب.',
            'college_id.exists'   => 'الكلية غير موجودة.',
            'department_id.exists' => 'القسم غير موجود.',
            'per_page.integer'    => 'عدد العناصر لكل صفحة يجب أن يكون عدداً صحيحاً.',
            'per_page.min'        => 'عدد العناصر لكل صفحة لا يمكن أن يقل عن 1.',
            'per_page.max'        => 'عدد العناصر لكل صفحة لا يمكن أن يتجاوز 100.',
            'page.integer'        => 'رقم الصفحة يجب أن يكون عدداً صحيحاً.',
            'page.min'            => 'رقم الصفحة لا يمكن أن يقل عن 1.',
        ];
    }
}
