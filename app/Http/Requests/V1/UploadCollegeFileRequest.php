<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UploadCollegeFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'student_year' => ['required', 'integer', 'min:1'],
            'student_semester'    => ['required', 'integer', 'in:1,2'],
            'college_id'  => ['required', 'exists:colleges,id'],
            'file'        => ['required', 'file', 'max:20480', 'mimes:pdf,xlsx,xls,png,jpg,jpeg'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'اسم الملف مطلوب.',
            'student_year.required'       => 'السنة الدراسية مطلوبة.',
            'student_semester.required'   => 'الفصل الدراسي مطلوب.',
            'college_id.required' => 'معرف الكلية مطلوب.',
            'college_id.exists'   => 'الكلية غير موجودة.',
            'file.required'       => 'الملف مطلوب.',
            'file.max'            => 'حجم الملف لا يتجاوز 20 ميجابايت.',
            'file.mimes'          => 'نوع الملف غير مسموح. الأنواع المسموحة: pdf, xlsx, xls, png, jpg, jpeg.',
        ];
    }
}
