<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // بدون صلاحيات
    }

    public function rules(): array
    {
        return [
            'department_id' => [
                'required',
                'integer',
                'exists:departments,id',
            ],
            'title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'person_id' => [
                'required',
                'integer',
                'exists:persons,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'department_id.required' => 'معرف القسم مطلوب.',
            'department_id.exists'   => 'القسم غير موجود.',
            'person_id.required'     => 'معرف الشخص مطلوب.',
            'person_id.exists'       => 'الشخص غير موجود.',
            'title.max'              => 'العنوان لا يتجاوز 255 حرفاً.',
        ];
    }
}
