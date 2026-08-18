<?php

namespace App\DTOs;

use App\Models\RequestType;
use Illuminate\Database\Eloquent\Collection;

class RequestTypeWithMediaDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly bool $teacher_acceptance,
        public readonly bool $college_dean_acceptance,
        public readonly bool $department_head_acceptance,
        public readonly bool $student_stuff_acceptance,
        public readonly bool $exams_stuff_acceptance,
        public readonly bool $doctor_acceptance,
        public readonly bool $requires_course,
        public readonly array $media,
        public readonly string $created_at,
        public readonly string $updated_at,
    ) {}
    public static function fromModel(RequestType $requestType, $media): self
    {
        $mediaArray = [];
        foreach ($media as $item) {
            $mediaArray[] = [
                'id' => $item->id,
                'request_type_id' => $item->request_type_id,
                'name' => $item->name,
                'type' => $item->type,
            ];
        }

        return new self(
            id: $requestType->id,
            name: $requestType->name,
            description: $requestType->description,
            teacher_acceptance: (bool) $requestType->teacher_acceptance,
            college_dean_acceptance: (bool) $requestType->college_dean_acceptance,
            department_head_acceptance: (bool) $requestType->department_head_acceptance,
            student_stuff_acceptance: (bool) $requestType->student_stuff_acceptance,
            exams_stuff_acceptance: (bool) $requestType->exams_stuff_acceptance,
            doctor_acceptance: (bool) $requestType->doctor_acceptance,
            requires_course: (bool) $requestType->requires_course,
            media: $mediaArray,
            created_at: $requestType->created_at->toISOString(),
            updated_at: $requestType->updated_at->toISOString(),
        );
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'teacher_acceptance' => $this->teacher_acceptance,
            'college_dean_acceptance' => $this->college_dean_acceptance,
            'department_head_acceptance' => $this->department_head_acceptance,
            'student_stuff_acceptance' => $this->student_stuff_acceptance,
            'exams_stuff_acceptance' => $this->exams_stuff_acceptance,
            'doctor_acceptance' => $this->doctor_acceptance,
            'requires_course' => $this->requires_course,
            'media' => $this->media,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
