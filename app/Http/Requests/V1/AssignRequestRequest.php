<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignRequestRequest extends FormRequest
{
    /**
     * تحديد إذا كان المستخدم مخولاً بتقديم هذا الطلب.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * إعداد البيانات للتحقق (دمج معاملات الرابط والـ Query).
     */
    protected function prepareForValidation(): void
    {
        // دمج request_id من الرابط و college_id من الـ Query مع البيانات
        $this->merge([
            'request_id' => $this->route('requestId'), // من {requestId} في الرابط
            'college_id' => $this->query('college_id'), // من ?college_id=...
        ]);
    }

    /**
     * قواعد التحقق من صحة البيانات.
     */
    public function rules(): array
    {
        return [
            'request_id' => [
                'required',
                'integer',
                Rule::exists('requests', 'id'),
            ],
            'college_id' => [
                'required',
                'integer',
                Rule::exists('colleges', 'id'),
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'request_id.required' => 'معرف الطلب مطلوب.',
            'request_id.exists' => 'الطلب المحدد غير موجود.',
            'college_id.required' => 'معرف الكلية مطلوب.',
            'college_id.exists' => 'الكلية غير موجودة.',
        ];
    }
}
