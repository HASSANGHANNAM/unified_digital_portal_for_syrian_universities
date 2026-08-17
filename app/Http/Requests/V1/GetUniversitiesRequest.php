<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetUniversitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'university_id' => [
                'nullable',
                'integer',
                'exists:universities,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'university_id.exists' => 'الجامعة المحددة غير موجودة.',
        ];
    }
}
