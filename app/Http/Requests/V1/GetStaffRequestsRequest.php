<?php

namespace App\Http\Requests\V1;

use App\Models\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetStaffRequestsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [

            'college_id' => [
                'required',
                'integer',
                'exists:colleges,id',
            ],

            'name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'per_page' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],

            'page' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'college_id.required' => 'معرف الكلية مطلوب.',
            'college_id.exists' => 'الكلية المحددة غير موجودة.',
            'per_page.min' => 'عدد العناصر في الصفحة يجب أن يكون على الأقل 1.',
            'per_page.max' => 'عدد العناصر في الصفحة لا يمكن أن يتجاوز 100.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->missing('per_page')) {
            $this->merge(['per_page' => 15]);
        }
    }
}
