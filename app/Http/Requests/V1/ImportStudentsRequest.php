<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ImportStudentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'ملف الطلاب مطلوب.',
            'file.file' => 'الملف المرسل غير صالح.',
            'file.mimes' => 'يجب أن يكون الملف بصيغة Excel.',
            'file.max' => 'حجم الملف يجب ألا يتجاوز 10MB.',
        ];
    }
}
