<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetCollegeFilesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page'      => ['nullable', 'integer', 'min:1'],
            'per_page'  => ['nullable', 'integer', 'min:1', 'max:100'],
            'student_year'      => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'upload_year' => ['nullable', 'integer', 'min:1', 'max:5'],
            'student_semester'  => ['nullable', 'integer', 'in:1,2'],
            'name'      => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer'     => 'رقم الصفحة يجب أن يكون عدداً صحيحاً.',
            'page.min'         => 'رقم الصفحة لا يمكن أن يقل عن 1.',
            'per_page.integer' => 'عدد العناصر لكل صفحة يجب أن يكون عدداً صحيحاً.',
            'per_page.min'     => 'عدد العناصر لكل صفحة لا يمكن أن يقل عن 1.',
            'per_page.max'     => 'عدد العناصر لكل صفحة لا يمكن أن يتجاوز 100.',
            'student_year.integer'     => 'السنة يجب أن تكون عدداً صحيحاً.',
            'student_year.min'         => 'السنة يجب أن تكون 2000 أو أكبر.',
            'student_year.max'         => 'السنة يجب أن تكون 2100 أو أقل.',
            'upload_year.integer' => 'السنة الحالية يجب أن تكون عدداً صحيحاً.',
            'upload_year.min'     => 'السنة الحالية يجب أن تكون 1 على الأقل.',
            'upload_year.max'     => 'السنة الحالية يجب أن تكون 5 على الأكثر.',
            'student_semester.in'      => 'الفصل يجب أن يكون 1 أو 2.',
        ];
    }
}
