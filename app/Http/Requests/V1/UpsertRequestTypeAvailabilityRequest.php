<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpsertRequestTypeAvailabilityRequest extends FormRequest
{
    /**
     * تحديد إذا كان المستخدم مخولاً بتقديم هذا الطلب.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * قواعد التحقق من صحة البيانات.
     */
    public function rules(): array
    {
        return [
            'is_available' => [
                'required',
                'boolean',
            ],
            'request_type_id' => [
                'required',
                'integer',
                'exists:request_types,id',
            ],
            'college_id' => [
                'required',
                'integer',
                'exists:colleges,id',
            ],
        ];
    }

    /**
     * رسائل الخطأ المخصصة.
     */
    public function messages(): array
    {
        return [
            'is_available.required' => 'حقل التوفر (is_available) مطلوب.',
            'is_available.boolean'  => 'حقل التوفر يجب أن يكون صحيحاً (true) أو خطأ (false).',

            'request_type_id.required' => 'معرف نوع الطلب مطلوب.',
            'request_type_id.integer'  => 'معرف نوع الطلب يجب أن يكون رقماً صحيحاً.',
            'request_type_id.exists'   => 'نوع الطلب المحدد غير موجود.',

            'college_id.required' => 'معرف الكلية مطلوب.',
            'college_id.integer'  => 'معرف الكلية يجب أن يكون رقماً صحيحاً.',
            'college_id.exists'   => 'الكلية المحددة غير موجودة.',
        ];
    }
}
