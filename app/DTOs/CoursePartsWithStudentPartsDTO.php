<?php

namespace App\DTOs;

class CoursePartsWithStudentPartsDTO
{
    public function __construct(
        public readonly int $coursePartId,
        public readonly string $coursePartName,
        public readonly float $coursePartPercentage,
        public readonly ?array $studentCoursePart,
    ) {}
    public static function fromArray(array $data): self
    {
        return new self(
            coursePartId: $data['course_part_id'],
            coursePartName: $data['course_part_name'],
            coursePartPercentage: $data['course_part_percentage'],
            studentCoursePart: $data['student_course_part'],
        );
    }
    public function toArray(): array
    {
        return [
            'course_part_id' => $this->coursePartId,
            'course_part_name' => $this->coursePartName,
            'course_part_percentage' => $this->coursePartPercentage,
            'student_course_part' => $this->studentCoursePart,
        ];
    }
}
