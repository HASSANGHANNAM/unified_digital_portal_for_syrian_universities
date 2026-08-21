<?php

namespace App\DTOs;

use App\Models\Department;

class DepartmentListWithHeadDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?array $head = null, // كائن رئيس القسم
    ) {}

    public static function fromModel(Department $department): self
    {
        $headData = null;
        if ($department->head && $department->head->doctor && $department->head->doctor->person) {
            $headData = [
                'id' => $department->head->id,
                'full_name' => $department->head->doctor->person->full_name,
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
