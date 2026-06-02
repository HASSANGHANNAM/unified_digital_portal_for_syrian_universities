<?php

namespace App\Http\Requests\V1;

use App\Models\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetStudentRequestsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'nullable',
                'string',
                Rule::in([
                    Request::STATUS_PENDING,
                    Request::STATUS_COMPLETED,
                    Request::STATUS_REJECTED,
                    Request::STATUS_CANCELLED,
                    Request::STATUS_UNIVERSITY_DIRECTOR_PROCESSING,
                    Request::STATUS_COLLEGE_DEAN,
                    Request::STATUS_DEPARTMENT_HEAD,
                    Request::STATUS_STUDENT_STUFF_PROCESSING,
                    Request::STATUS_EXAMS_STUFF,
                    Request::STATUS_DOCTOR_PROCESSING,
                ]),
            ],
            'name' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
