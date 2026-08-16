<?php

namespace App\DTOs;

class TaListDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $ta_id_number,
        public readonly ?array $department,
        public readonly string $full_name,
    ) {}

    public static function fromModel($ta): self
    {
        $department = null;
        if ($ta->department) {
            $department = [
                'id' => $ta->department->id,
                'name' => $ta->department->name,
                'college' => $ta->department->college ? [
                    'id' => $ta->department->college->id,
                    'name' => $ta->department->college->name,
                ] : null,
            ];
        }

        return new self(
            id: $ta->id,
            ta_id_number: $ta->ta_id_number,
            department: $department,
            full_name: $ta->person?->full_name ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'ta_id_number' => $this->ta_id_number,
            'department' => $this->department,
        ];
    }
}
