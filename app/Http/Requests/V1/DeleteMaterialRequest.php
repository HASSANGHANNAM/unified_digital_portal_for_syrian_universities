<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DeleteMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
