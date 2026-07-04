<?php
// app/Http/Requests/UploadSignatureRequest.php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UploadSignatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'signature' => [
                'required',
                'string',
                'regex:/^data:image\/(png|jpeg|jpg);base64,/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'signature.required' => 'صورة التوقيع مطلوبة',
            'signature.regex' => 'صيغة Base64 غير صحيحة (يفضل PNG)',
        ];
    }
}
