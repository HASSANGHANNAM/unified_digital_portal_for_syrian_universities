<?php

namespace App\DTOs;

use App\Models\Request;

class RequestDetailsDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $student_id,
        public readonly string $student_name,
        public readonly string $request_type_id,
        public readonly ?string $reason,
        public readonly ?string $submission_date,
        public readonly string $status,
        public readonly ?string $decision_date,
        public readonly ?string $decision_reason,
        public readonly ?string $processed_by_staff_id,
        public readonly ?string $course_id,
        public readonly ?string $request_type_name,
        public readonly ?string $request_type_description,
        public readonly string $pdf_url,
        public readonly array $media
    ) {}

    public static function fromModel(Request $request): self
    {
        // تحميل العلاقات المطلوبة (تأكد من وجود student.person)
        $request->loadMissing(['requestType', 'media', 'student.person']);

        // جلب اسم الطالب من جدول persons عبر العلاقة
        $studentName = $request->student?->person?->full_name ?? '';

        // إنشاء رابط PDF (إذا كان هناك مسار)
        $pdfUrl = $request->id ? '/api/V1/pdf/' . $request->id : '';        // تنسيق التواريخ
        $submission = $request->submission_date instanceof \DateTime
            ? $request->submission_date->format('Y-m-d H:i:s')
            : (string) $request->submission_date;

        $decision = $request->decision_date instanceof \DateTime
            ? $request->decision_date->format('Y-m-d H:i:s')
            : (string) $request->decision_date;

        // تحويل وسائط الطلب (media) إلى DTO
        $media = collect($request->media)
            ->map(fn($item) => RequestMediaDTO::fromModel($item)->toArray())
            ->toArray();

        return new self(
            (string) $request->id,
            (string) $request->student_id,
            $studentName,
            (string) $request->request_type_id,
            $request->reason ? (string) $request->reason : null,
            $submission ?: null,
            (string) $request->status,
            $decision ?: null,
            $request->decision_reason ? (string) $request->decision_reason : null,
            $request->processed_by_staff_id ? (string) $request->processed_by_staff_id : null,
            $request->course_id ? (string) $request->course_id : null,
            $request->requestType?->name ? (string) $request->requestType->name : null,
            $request->requestType?->description ? (string) $request->requestType->description : null,
            $pdfUrl,
            $media
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'student_name' => $this->student_name,
            'request_type_id' => $this->request_type_id,
            'request_type_name' => $this->request_type_name,
            'request_type_description' => $this->request_type_description,
            'status' => $this->status,
            'reason' => $this->reason,
            'submission_date' => $this->submission_date,
            'decision_date' => $this->decision_date,
            'decision_reason' => $this->decision_reason,
            'processed_by_staff_id' => $this->processed_by_staff_id,
            'course_id' => $this->course_id,
            'pdf_url' => $this->pdf_url,
            'media' => $this->media,
        ];
    }
}
