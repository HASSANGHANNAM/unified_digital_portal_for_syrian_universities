<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreSanctionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sanction_type_id' => 'required|exists:sanction_types,id',
            'issued_date' => 'required|date',
            'expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'student_id' => 'required|exists:students,id',
        ];
    }
}
