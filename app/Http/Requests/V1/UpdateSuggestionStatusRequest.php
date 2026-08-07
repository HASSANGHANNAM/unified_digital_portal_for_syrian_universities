<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSuggestionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in(['تم القبول', 'تم الرفض']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'الحالة مطلوبة.',
            'status.string'   => 'الحالة يجب أن تكون نصاً.',
            'status.in'       => 'الحالة يجب أن تكون إما "تم القبول" أو "تم الرفض".',
        ];
    }
}
