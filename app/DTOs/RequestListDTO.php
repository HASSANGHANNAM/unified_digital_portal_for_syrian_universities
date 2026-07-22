<?php

namespace App\DTOs;

use App\Models\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RequestListDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $student_id,
        public readonly string $status,          // الحالة الأصلية (داخلية)
        public readonly string $display_status,  // الحالة المبسطة للواجهة (جديدة)
        public readonly string $request_type_id,
        public readonly ?string $submission_date,
        public readonly ?string $request_type_name
    ) {}

    public static function fromModel(Request $request): self
    {
        // معالجة تاريخ التقديم
        $submission = null;
        if ($request->submission_date instanceof \DateTime) {
            $submission = $request->submission_date->format('Y-m-d H:i:s');
        } elseif (!empty($request->submission_date)) {
            $submission = (string) $request->submission_date;
        }

        // 🔥 الحصول على الحالة المبسطة (waiting_doctor بدلاً من generating_doctor_pdf)
        $displayStatus = $request->getDisplayStatus();

        return new self(
            (string) $request->id,
            (string) $request->student_id,
            (string) $request->status,          // الحالة الداخلية (generating_...)
            $displayStatus,                     // الحالة المعروضة للمستخدم (waiting_...)
            (string) $request->request_type_id,
            $submission,
            $request->requestType?->name ? (string) $request->requestType->name : null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'status' => $this->display_status,   // 🔥 نعيد للمستخدم الحالة المبسطة
            'request_type_id' => $this->request_type_id,
            'submission_date' => $this->submission_date,
            'request_type_name' => $this->request_type_name,
        ];
    }

    public static function fromRequest(Request $request): array
    {
        return self::fromModel($request)->toArray();
    }

    public static function fromPaginator(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => $paginator->getCollection()->map(fn($request) => self::fromRequest($request))->toArray(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ];
    }

    /**
     * 🔥 تابع جديد خاص بـ Staff Requests
     * يستخدم العلاقات المتداخلة: course.universalCourse و processedBy.person
     */
    public static function fromPaginatorForStaff(LengthAwarePaginator $paginator): array
    {
        return [
            'requests' => $paginator->getCollection()->map(function ($request) {
                // استخراج اسم المقرر من universalCourse
                $course = null;
                if ($request->course && $request->course->universalCourse) {
                    $course = [
                        'name' => $request->course->universalCourse->name,
                        'code' => $request->course->code,
                    ];
                }

                // استخراج اسم الموظف من person
                $staff = null;
                if ($request->processedBy && $request->processedBy->person) {
                    $staff = [
                        'name' => $request->processedBy->person->full_name,
                    ];
                }

                return [
                    'request_id'      => $request->id,
                    'request_type_id' => $request->request_type_id,
                    'reason'          => $request->reason,
                    'submission_date' => $request->submission_date?->toISOString() ?? (string) $request->submission_date,
                    'decision_date'   => $request->decision_date?->toISOString(),
                    'decision_reason' => $request->decision_reason,
                    'course'          => $course,
                    'status'          => $request->status,
                    'staff'           => $staff,
                ];
            })->toArray(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ];
    }
}
