<?php

namespace App\DTOs;

use App\Models\Suggestion;

class CollegeSuggestionDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $content,
        public readonly string $status,
        public readonly string $studentId,
        public readonly string $createdAt,
        public readonly ?string $updatedAt,
        public readonly ?string $fullname,
        public readonly ?string $student_id_number,
        public readonly ?string $academic_status,
    ) {}

    public static function fromModel(Suggestion $suggestion): self
    {
        $student = $suggestion->student;
        $person = $student?->person;

        return new self(
            id: $suggestion->id,
            content: $suggestion->content,
            status: $suggestion->status,
            studentId: $suggestion->student_id,
            createdAt: $suggestion->created_at->toDateTimeString(),
            updatedAt: $suggestion->updated_at?->toDateTimeString(),
            fullname: $person?->full_name ?? null,
            student_id_number: $student?->student_id_number ?? null,
            academic_status: $student?->academic_status ?? null,
        );
    }
    public static function fromServiceData(iterable $suggestions): array
    {
        $result = [];
        foreach ($suggestions as $suggestion) {
            $result[] = self::fromModel($suggestion);
        }
        return $result;
    }
}
