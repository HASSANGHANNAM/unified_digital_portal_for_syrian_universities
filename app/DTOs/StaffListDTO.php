<?php

namespace App\DTOs;

class StaffListDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $staff_id_number,
        public readonly ?array $department,
        public readonly string $full_name,
    ) {}

    public static function fromModel($staff): self
    {
        $department = null;
        if ($staff->department) {
            $department = [
                'id' => $staff->department->id,
                'name' => $staff->department->name,
                'college' => $staff->department->college ? [
                    'id' => $staff->department->college->id,
                    'name' => $staff->department->college->name,
                ] : null,
            ];
        }

        return new self(
            id: $staff->id,
            staff_id_number: $staff->staff_id_number,
            department: $department,
            full_name: $staff->person?->full_name ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'staff_id_number' => $this->staff_id_number,
            'department' => $this->department,
        ];
    }
}
