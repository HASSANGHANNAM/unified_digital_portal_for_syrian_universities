<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetDepartmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'college_id' => [
                'nullable',
                'integer',
                'exists:colleges,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'college_id.exists' => 'الكلية المحددة غير موجودة.',
        ];
    }
}
