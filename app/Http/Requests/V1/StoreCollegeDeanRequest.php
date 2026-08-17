<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollegeDeanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // لا صلاحيات
    }

    public function rules(): array
    {
        return [
            'doctor_id' => [
                'required',
                'integer',
                'exists:doctors,id',
            ],
            'hired_date' => [
                'required',
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
            'hired_date.required' => 'تاريخ التعيين مطلوب.',
            'hired_date.date'    => 'يجب إدخال تاريخ صحيح.',
        ];
    }
}
