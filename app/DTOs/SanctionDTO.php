<?php

namespace App\DTOs;

use App\Models\Sanction;

class SanctionDTO
{
    public function __construct(
        public string $id,
        public int $sanction_type_id,
        public string $status,
        public ?string $issued_date,
        public ?string $expiry_date,
        public ?string $notes,
        public ?string $student_response,
        public ?string $staff_response,
        public int $student_id,
        public int $staff_id,
        public ?int $course_id,
    ) {}

    public static function fromModel(Sanction $model): self
    {
        return new self(
            (string) $model->id,
            (int) $model->sanction_type_id,
            $model->status ?? '',
            $model->issued_date?->toDateString() ?? null,
            $model->expiry_date?->toDateString() ?? null,
            $model->notes ?? null,
            $model->student_response ?? null,
            $model->staff_response ?? null,
            (int) $model->student_id,
            (int) $model->staff_id,
            $model->course_id ? (int) $model->course_id : null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sanction_type_id' => $this->sanction_type_id,
            'status' => $this->status,
            'issued_date' => $this->issued_date,
            'expiry_date' => $this->expiry_date,
            'notes' => $this->notes,
            'student_response' => $this->student_response,
            'staff_response' => $this->staff_response,
            'student_id' => $this->student_id,
            'staff_id' => $this->staff_id,
            'course_id' => $this->course_id,
        ];
    }
}
