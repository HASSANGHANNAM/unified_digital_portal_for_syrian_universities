<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseStaffRequest extends FormRequest
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
            'course_id' => [
                'required',
                'integer',
                'exists:courses,id',
            ],
            'is_advisor' => [
                'required',
                'boolean',
            ],
            'doctor_id' => [
                'required_without:ta_id', // مطلوب إذا لم يُرسل ta_id
                'nullable',
                'integer',
                'exists:doctors,id',
                'prohibits:ta_id', // لا يمكن إرساله مع ta_id
            ],
            'ta_id' => [
                'required_without:doctor_id', // مطلوب إذا لم يُرسل doctor_id
                'nullable',
                'integer',
                'exists:teaching_assistants,id',
                'prohibits:doctor_id', // لا يمكن إرساله مع doctor_id
            ],
        ];
    }

    /**
     * رسائل الخطأ المخصصة.
     */
    public function messages(): array
    {
        return [
            // رسائل course_id
            'course_id.required' => 'معرف المادة (course_id) مطلوب.',
            'course_id.integer' => 'معرف المادة يجب أن يكون رقماً صحيحاً.',
            'course_id.exists' => 'المادة المحددة غير موجودة.',

            // رسائل is_advisor
            'is_advisor.required' => 'حقل المستشار (is_advisor) مطلوب.',
            'is_advisor.boolean' => 'حقل المستشار يجب أن يكون صحيحاً (true) أو خطأ (false).',

            // رسائل doctor_id
            'doctor_id.required_without' => 'يجب إرسال إما doctor_id أو ta_id.',
            'doctor_id.integer' => 'معرف الدكتور يجب أن يكون رقماً صحيحاً.',
            'doctor_id.exists' => 'الدكتور المحدد غير موجود.',
            'doctor_id.prohibits' => 'لا يمكن إرسال doctor_id مع ta_id معاً، يرجى اختيار واحد فقط.',

            // رسائل ta_id
            'ta_id.required_without' => 'يجب إرسال إما doctor_id أو ta_id.',
            'ta_id.integer' => 'معرف المعيد يجب أن يكون رقماً صحيحاً.',
            'ta_id.exists' => 'المعيد المحدد غير موجود.',
            'ta_id.prohibits' => 'لا يمكن إرسال ta_id مع doctor_id معاً، يرجى اختيار واحد فقط.',
        ];
    }
}
