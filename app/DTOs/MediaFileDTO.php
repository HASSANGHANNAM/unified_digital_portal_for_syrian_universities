<?php

namespace App\DTOs;

use App\Models\RequestMedia;

class MediaFileDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $type,
        public readonly ?string $path,
        public readonly ?string $studentId
    ) {}

    public static function fromModel(RequestMedia $media): self
    {
        return new self(
            (string) $media->id,
            (string) $media->name,
            (string) $media->type,
            $media->path,
            isset($media->request->student_id) ? (string) $media->request->student_id : null
        );
    }
}
