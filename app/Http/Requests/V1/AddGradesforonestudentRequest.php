<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AddGradesforonestudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_number' => 'required|string',
            'parts' => 'required|array|min:1',
            'parts.*.course_part_id' => 'required|integer|exists:course_parts,id',
            'parts.*.grade' => 'required|numeric|min:0',

        ];
    }
}
