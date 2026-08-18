<?php

namespace App\DTOs;

use App\Models\Sanction;

class SanctionListDTO
{
    public function __construct(
        public readonly int $id,
        public readonly array $sanction_type,
        public readonly string $status,
        public readonly string $issued_date,
        public readonly ?string $expiry_date,
        public readonly ?string $notes,
        public readonly ?string $student_response,
        public readonly ?string $staff_response,
        public readonly ?array $staff,
        public readonly ?array $course,
    ) {}

    public static function fromModel(Sanction $sanction): self
    {
        $sanctionType = [
            'id' => $sanction->sanctionType?->id,
            'name' => $sanction->sanctionType?->name,
            'reason' => $sanction->sanctionType?->reason,
            'years' => $sanction->sanctionType?->years ?? 0,
            'months' => $sanction->sanctionType?->months ?? 0,
            'days' => $sanction->sanctionType?->days ?? 0,
        ];
        $staff = null;
        if ($sanction->staff && $sanction->staff->person) {
            $staff = [
                'id' => $sanction->staff->id,
                'full_name' => $sanction->staff->person->full_name,
            ];
        }
        $course = null;
        if ($sanction->course) {
            $course = [
                'id' => $sanction->course->id,
                'name' => $sanction->course->name,
                'universal_course' => $sanction->course->universalCourse ? [
                    'id' => $sanction->course->universalCourse->id,
                    'name' => $sanction->course->universalCourse->name,
                ] : null,
            ];
        }

        return new self(
            id: $sanction->id,
            sanction_type: $sanctionType,
            status: $sanction->status,
            issued_date: $sanction->issued_date->format('Y-m-d'),
            expiry_date: $sanction->expiry_date?->format('Y-m-d'),
            notes: $sanction->notes,
            student_response: $sanction->student_response,
            staff_response: $sanction->staff_response,
            staff: $staff,
            course: $course,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sanction_type' => $this->sanction_type,
            'status' => $this->status,
            'issued_date' => $this->issued_date,
            'expiry_date' => $this->expiry_date,
            'notes' => $this->notes,
            'student_response' => $this->student_response,
            'staff_response' => $this->staff_response,
            'staff' => $this->staff,
            'course' => $this->course,
        ];
    }
}
