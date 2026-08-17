<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentHeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // بدون صلاحيات
    }

    public function rules(): array
    {
        return [
            'doctor_id' => [
                'required',
                'integer',
                'exists:doctors,id',
            ],
            'expire_date' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.required' => 'معرف الدكتور مطلوب.',
            'doctor_id.exists'   => 'الدكتور غير موجود.',
            'expire_date.date'   => 'تاريخ الانتهاء يجب أن يكون تاريخاً صحيحاً.',
        ];
    }
}
