<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetAdvertisementDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:advertisements,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'معرف الإعلان مطلوب.',
            'id.exists'   => 'الإعلان غير موجود.',
        ];
    }

    /**
     * Get the validated ID directly.
     */
    public function getAdvertisementId(): int
    {
        return (int) $this->validated('id');
    }
}
