<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UploadCollegeLogoRequest extends FormRequest
{

    public function authorize(): bool
    {
        return TRUE;
    }
    public function rules(): array
    {
        return [
            'logo' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'logo.required' => 'صورة الشعار مطلوبة',
            'logo.image' => 'الملف يجب أن يكون صورة',
            'logo.mimes' => 'الصيغ المسموحة: jpeg, png, jpg',
            'logo.max' => 'حجم الصورة لا يتجاوز 2 ميجابايت',
        ];
    }
}
