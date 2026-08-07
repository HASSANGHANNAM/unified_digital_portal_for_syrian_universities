<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AddStudentSuggestions extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => [
                'required',
                'string',
                'max:1000',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'المحتوى مطلوب.',
            'content.string'   => 'المحتوى يجب أن يكون نصاً.',
            'content.max'      => 'المحتوى لا يمكن أن يتجاوز 1000 حرف.',
        ];
    }
}
