<?php

namespace App\DTOs;

use App\Models\Course;

class CourseDetailsDTO
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public int $credits,
        public int $college_id,
        public int $department_id,
        public array $parts,
    ) {}

    public static function fromModel(Course $model): self
    {
        $partsCollection = $model->relationLoaded('courseParts')
            ? $model->courseParts
            : ($model->relationLoaded('parts') ? $model->parts : collect());

        $parts = $partsCollection->map(function ($part) {
            return [
                'id' => (int) $part->id,
                'name' => (string) $part->name,
                'percentage' => (int) $part->percentage,
            ];
        })->values()->all();
        $partsCollection = $model->relationLoaded('universalCourse')
            ? $model->name
            : ($model->relationLoaded('universalCourse') ? $model->universalCourse->name : null);
        return new self(
            (int) $model->id,
            (string) $model->code,
            (string) $model->name,
            (int) $model->credits,
            (int) $model->college_id,
            (int) $model->department_id,
            $parts,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'credits' => $this->credits,
            'college_id' => $this->college_id,
            'department_id' => $this->department_id,
            'parts' => $this->parts,
        ];
    }
}
