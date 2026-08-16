<?php

namespace App\DTOs;

class DoctorListDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $doctor_id_number,
        public readonly ?array $department,
        public readonly string $full_name,
    ) {}

    public static function fromModel($doctor): self
    {
        $department = null;
        if ($doctor->department) {
            $department = [
                'id' => $doctor->department->id,
                'name' => $doctor->department->name,
                'college' => $doctor->department->college ? [
                    'id' => $doctor->department->college->id,
                    'name' => $doctor->department->college->name,
                ] : null,
            ];
        }

        return new self(
            id: $doctor->id,
            doctor_id_number: $doctor->doctor_id_number,
            department: $department,
            full_name: $doctor->person?->full_name ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'doctor_id_number' => $this->doctor_id_number,
            'full_name' => $this->full_name,
            'department' => $this->department,
        ];
    }
}
