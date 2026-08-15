<?php

namespace App\DTOs;

use App\Models\StudentCourse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CourseStudentListDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $course_id,
        public readonly float $credits,
        public readonly string $status,
        public readonly ?string $academic_year,
        public readonly ?int $semester,
        public readonly int $student_id,
        public readonly string $student_id_number,
        public readonly string $full_name,
        public readonly float $total,

    ) {}

    public static function fromModel(StudentCourse $studentCourse): self
    {
        $total = $studentCourse->parts->sum('credits') ?: $studentCourse->credits;

        return new self(
            id: $studentCourse->id,
            course_id: $studentCourse->course_id,
            credits: $studentCourse->credits,
            status: $studentCourse->status,
            academic_year: $studentCourse->academic_year,
            semester: $studentCourse->semester,
            student_id: $studentCourse->student_id,
            student_id_number: $studentCourse->student?->student_id_number ?? '',
            full_name: $studentCourse->student?->person?->full_name ?? '',
            total: $total,
        );
    }
    public static function fromPaginator(LengthAwarePaginator $paginator): array
    {
        return [
            'studentsCourses' => $paginator->getCollection()->map(fn($item) => self::fromModel($item)->toArray())->toArray(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ];
    }
    public function toArray(): array
    {
        return [
            'student_course_id' => $this->id,
            'course_id' => $this->course_id,
            'credits' => $this->credits,
            'status' => $this->status,
            'academic_year' => $this->academic_year,
            'semester' => $this->semester,
            'student_id' => $this->student_id,
            'student_id_number' => $this->student_id_number,
            'full_name' => $this->full_name,
            'total' => $this->total,
        ];
    }
}
