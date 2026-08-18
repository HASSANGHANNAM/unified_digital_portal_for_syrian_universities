<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequestTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('request_types', 'name'),
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'teacher_acceptance' => [
                'nullable',
                'boolean',
            ],
            'college_dean_acceptance' => [
                'nullable',
                'boolean',
            ],
            'department_head_acceptance' => [
                'nullable',
                'boolean',
            ],
            'student_stuff_acceptance' => [
                'nullable',
                'boolean',
            ],
            'exams_stuff_acceptance' => [
                'nullable',
                'boolean',
            ],
            'doctor_acceptance' => [
                'nullable',
                'boolean',
            ],
            'requires_course' => [
                'nullable',
                'boolean',
            ],

            'media' => [
                'nullable',
                'array',
            ],
            'media.*.name' => [
                'required_with:media',
                'string',
                'max:255',
            ],
            'media.*.type' => [
                'required_with:media',
                'string',
                Rule::in(['pdf', 'image', 'doc', 'docx', 'xls', 'xlsx', 'video', 'audio', 'other']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم نوع الطلب مطلوب.',
            'name.unique' => 'اسم نوع الطلب موجود مسبقاً.',

            'teacher_acceptance.boolean' => 'قبول المعلم يجب أن يكون صحيحاً أو خطأ.',
            'college_dean_acceptance.boolean' => 'قبول عميد الكلية يجب أن يكون صحيحاً أو خطأ.',
            'department_head_acceptance.boolean' => 'قبول رئيس القسم يجب أن يكون صحيحاً أو خطأ.',
            'student_stuff_acceptance.boolean' => 'قبول شؤون الطلاب يجب أن يكون صحيحاً أو خطأ.',
            'exams_stuff_acceptance.boolean' => 'قبول الامتحانات يجب أن يكون صحيحاً أو خطأ.',
            'doctor_acceptance.boolean' => 'قبول الدكتور يجب أن يكون صحيحاً أو خطأ.',
            'requires_course.boolean' => 'يتطلب المادة يجب أن يكون صحيحاً أو خطأ.',

            'media.array' => 'يجب أن تكون المرفقات مصفوفة.',
            'media.*.name.required_with' => 'اسم المرفق مطلوب.',
            'media.*.name.string' => 'اسم المرفق يجب أن يكون نصاً.',
            'media.*.type.required_with' => 'نوع المرفق مطلوب.',
            'media.*.type.in' => 'نوع المرفق غير مدعوم. الأنواع المسموحة: pdf, image, doc, docx, xls, xlsx, video, audio, other.',
        ];
    }
    protected function prepareForValidation(): void
    {
        $booleanFields = [
            'teacher_acceptance',
            'college_dean_acceptance',
            'department_head_acceptance',
            'student_stuff_acceptance',
            'exams_stuff_acceptance',
            'doctor_acceptance',
            'requires_course',
        ];

        foreach ($booleanFields as $field) {
            if ($this->missing($field)) {
                $this->merge([$field => false]);
            } elseif ($this->has($field) && $this->input($field) === '') {
                $this->merge([$field => false]);
            }
        }
    }
}
