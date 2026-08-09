<?php

namespace App\Http\Requests\V1;

use App\Enums\NotificationColor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BroadcastNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowedTypes = implode(',', array_column(NotificationColor::cases(), 'name'));
        return [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string|in:' . $allowedTypes,
            'attachment' => 'nullable|array',
            'attachment.*' => 'file|max:20480|mimes:jpg,jpeg,png,mp4,pdf,doc,docx,xls,xlsx,csv',
            'student_id' => 'sometimes|integer|exists:students,id',
            'college_id' => 'sometimes|integer|exists:colleges,id',
            'department_id' => 'sometimes|integer|exists:departments,id',
            'academic_year' => 'sometimes|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'نوع الإشعار غير صحيح. القيم المسموحة: ' . implode(', ', array_column(NotificationColor::cases(), 'name')),
            'attachment.*.mimes' => 'نوع الملف غير مسموح. الأنواع المسموحة: jpg, jpeg, png, mp4, pdf, doc, docx, xls, xlsx, csv.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $hasStudent = $this->filled('student_id');
            $hasDepartment = $this->filled('department_id');
            $hasCollege = $this->filled('college_id');
            $hasAcademicYear = $this->filled('academic_year');

            if (! $hasStudent && ! $hasDepartment && ! $hasCollege) {
                $validator->errors()->add('student_id', 'يجب تحديد student_id أو department_id أو college_id على الأقل.');
            }

            if ($hasStudent && ($hasDepartment || $hasCollege)) {
                $validator->errors()->add('student_id', 'student_id لا يمكن استخدامه مع department_id أو college_id.');
            }

            if ($hasDepartment && $hasCollege) {
                $validator->errors()->add('department_id', 'department_id و college_id لا يمكن إرسالهما معًا.');
            }

            if ($hasAcademicYear && $hasStudent) {
                $validator->errors()->add('academic_year', 'academic_year لا يمكن استخدامه مع student_id.');
            }

            if ($hasAcademicYear && ! ($hasDepartment || $hasCollege)) {
                $validator->errors()->add('academic_year', 'academic_year يجب أن يُستخدم مع department_id أو college_id.');
            }
            $type = $this->input('type');
            $uploaded = $this->file('attachment') ?? [];
        });
    }
}
