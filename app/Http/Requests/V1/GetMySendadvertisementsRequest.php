<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetMySendadvertisementsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'page'     => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
    public function messages(): array
    {

        return [
            'page.integer'     => 'رقم الصفحة يجب أن يكون عدداً صحيحاً.',
            'page.min'         => 'رقم الصفحة لا يمكن أن يقل عن 1.',
            'per_page.integer' => 'عدد العناصر لكل صفحة يجب أن يكون عدداً صحيحاً.',
            'per_page.min'     => 'عدد العناصر لكل صفحة لا يمكن أن يقل عن 1.',
            'per_page.max'     => 'عدد العناصر لكل صفحة لا يمكن أن يتجاوز 100.',
        ];
    }
}
