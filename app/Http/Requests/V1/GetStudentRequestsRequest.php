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
                    // الحالات الأساسية
                    Request::STATUS_PENDING,

                    // حالات التجهيز (Generating)
                    Request::STATUS_GENERATING_DOCTOR,
                    Request::STATUS_GENERATING_EXAMS_STUFF,
                    Request::STATUS_GENERATING_STUDENT_STUFF,
                    Request::STATUS_GENERATING_DEPARTMENT_HEAD,
                    Request::STATUS_GENERATING_COLLEGE_DEAN,
                    Request::STATUS_GENERATING_TEACHER,

                    // حالات الانتظار (Waiting)
                    Request::STATUS_WAITING_DOCTOR,
                    Request::STATUS_WAITING_EXAMS_STUFF,
                    Request::STATUS_WAITING_STUDENT_STUFF,
                    Request::STATUS_WAITING_DEPARTMENT_HEAD,
                    Request::STATUS_WAITING_COLLEGE_DEAN,
                    Request::STATUS_WAITING_TEACHER,

                    // الحالات النهائية
                    Request::STATUS_COMPLETED,
                    Request::STATUS_REJECTED,
                    Request::STATUS_CANCELLED,
                ]),
            ],
            'name' => ['nullable', 'string'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
