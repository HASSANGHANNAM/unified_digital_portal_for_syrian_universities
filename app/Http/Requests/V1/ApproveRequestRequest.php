<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApproveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'request_user_id' => $this->route('requestUserId'),
        ]);
    }

    public function rules(): array
    {
        return [
            'request_id' => [
                'required',
                'integer',
                Rule::exists('request', 'id'),
            ],
            'decision' => [
                'required',
                'string',
                Rule::in(['approved', 'rejected']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'request_id.required' => 'معرف التعيين مطلوب.',
            'request_id.exists' => 'سجل التعيين غير موجود.',
            'decision.required' => 'يجب تحديد القرار (approved أو rejected).',
            'decision.in' => 'القرار يجب أن يكون approved أو rejected.',
        ];
    }
}
