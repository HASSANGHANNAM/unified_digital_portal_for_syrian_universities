<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeachingAssistantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'department_id' => [
                'required',
                'integer',
                'exists:departments,id',
            ],
            'supervisor_id' => [
                'required',
                'integer',
                'exists:doctors,id',
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
            'supervisor_id.required' => 'معرف المشرف (الدكتور) مطلوب.',
            'supervisor_id.exists'   => 'المشرف غير موجود.',
            'person_id.required'     => 'معرف الشخص مطلوب.',
            'person_id.exists'       => 'الشخص غير موجود.',
        ];
    }
}
