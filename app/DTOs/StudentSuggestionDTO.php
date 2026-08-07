<?php

namespace App\DTOs;

use App\Models\Suggestion;

class StudentSuggestionDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $content,
        public readonly string $status,
        public readonly string $studentId,
        public readonly string $submission_date,
        public readonly string $createdAt,
        public readonly ?string $updatedAt,
    ) {}
    public static function fromModel(Suggestion $suggestion): self
    {
        return new self(
            id: $suggestion->id,
            content: $suggestion->content,
            status: $suggestion->status,
            studentId: $suggestion->student_id,
            submission_date: $suggestion->submission_date,
            createdAt: $suggestion->created_at->toDateTimeString(),
            updatedAt: $suggestion->updated_at?->toDateTimeString(),
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
