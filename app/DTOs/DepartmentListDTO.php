<?php

namespace App\DTOs;

class DepartmentListDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}

    public static function fromModel($department): self
    {
        return new self(
            id: $department->id,
            name: $department->name,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
