<?php

namespace App\DTOs;

use App\Models\Student;

class CollegeStudentDTO
{
    public function __construct(
        public int $student_id,
        public string $student_id_number,
        public string $full_name,
        public string $major,
        public int $enrollment_year,
        public float $current_gpa,
        public int $department_id,
        public int $college_id,
    ) {}

    public static function fromModel(Student $model): self
    {
        return new self(
            (int) $model->id,
            (string) $model->student_id_number,
            (string) ($model->person->full_name ?? ''),
            (string) $model->major,
            (int) $model->enrollment_year,
            (float) $model->current_gpa,
            (int) $model->department_id,
            (int) $model->college_id,
        );
    }

    public function toArray(): array
    {
        return [
            'student_id' => $this->student_id,
            'student_id_number' => $this->student_id_number,
            'full_name' => $this->full_name,
            'major' => $this->major,
            'enrollment_year' => $this->enrollment_year,
            'current_gpa' => $this->current_gpa,
            'department_id' => $this->department_id,
            'college_id' => $this->college_id,
        ];
    }
}
