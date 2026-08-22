<?php

namespace App\DTOs;

use App\Models\Department;

class DepartmentListWithHeadDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?array $head = null,
    ) {}

    public static function fromModel(Department $department): self
    {
        $headData = null;

        $firstHead = $department->heads->first();

        if ($firstHead && $firstHead->doctor && $firstHead->doctor->person) {
            $headData = [
                'id' => $firstHead->id,
                'full_name' => $firstHead->doctor->person->full_name,
            ];
        }

        return new self(
            id: $department->id,
            name: $department->name,
            head: $headData,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'head' => $this->head,
        ];
    }
}
