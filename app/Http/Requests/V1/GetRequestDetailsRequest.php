<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class GetRequestDetailsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->route('requestId') !== null) {
            $this->merge([
                'requestId' => $this->route('requestId'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'requestId' => ['required', 'integer', 'min:1'],
        ];
    }
}
