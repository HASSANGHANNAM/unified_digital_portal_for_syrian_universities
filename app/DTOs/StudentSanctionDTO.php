<?php

namespace App\DTOs;

use App\Models\Sanction;
use DateTimeInterface;

class StudentSanctionDTO
{
    public function __construct(
        public int $sanction_id,
        public ?string $type_name,
        public ?string $reason,
        public string $status,
        public ?string $issued_date,
        public ?string $expiry_date,
        public ?string $notes,
        public ?string $student_response,
        public ?string $staff_response,
        public ?int $course_id,
        public ?int $staff_id,
    ) {}

    public static function fromModel(Sanction $model): self
    {
        return new self(
            (int) $model->id,
            $model->sanctionType?->name ? (string) $model->sanctionType->name : null,
            $model->sanctionType?->reason ? (string) $model->sanctionType->reason : null,
            (string) $model->status,
            self::formatDate($model->issued_date),
            self::formatDate($model->expiry_date),
            $model->notes ? (string) $model->notes : null,
            $model->student_response ? (string) $model->student_response : null,
            $model->staff_response ? (string) $model->staff_response : null,
            $model->course_id ? (int) $model->course_id : null,
            $model->staff_id ? (int) $model->staff_id : null,
        );
    }

    private static function formatDate($value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        return $value ? (string) $value : null;
    }

    public function toArray(): array
    {
        return [
            'sanction_id' => $this->sanction_id,
            'type_name' => $this->type_name,
            'reason' => $this->reason,
            'status' => $this->status,
            'issued_date' => $this->issued_date,
            'expiry_date' => $this->expiry_date,
            'notes' => $this->notes,
            'student_response' => $this->student_response,
            'staff_response' => $this->staff_response,
            'course_id' => $this->course_id,
            'staff_id' => $this->staff_id,
        ];
    }
}
