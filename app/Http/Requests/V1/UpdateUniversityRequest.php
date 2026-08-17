<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUniversityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address' => [
                'nullable',
                'string',
                'max:500',
            ],
            'logo' => [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,gif,svg,webp',
                'max:2048', // 2 ميجابايت
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'address.max' => 'العنوان لا يتجاوز 500 حرف.',
            'logo.mimes' => 'نوع الملف غير مسموح. الأنواع المسموحة: jpeg, png, jpg, gif, svg, webp.',
            'logo.max' => 'حجم الملف لا يتجاوز 2 ميجابايت.',
        ];
    }
}
