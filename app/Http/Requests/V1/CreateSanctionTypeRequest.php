<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreateSanctionTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:sanction_types,name',
            'reason' => 'required|string',
            'years' => 'nullable|integer',
            'months' => 'nullable|integer',
            'days' => 'nullable|integer',
        ];
    }
}
