<?php

namespace App\DTOs;

class DocumentUploadDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $path,
        public readonly string $url
    ) {}

    public static function fromModel($attachment): self
    {
        return new self(
            id: $attachment->id,
            name: $attachment->name,
            path: $attachment->path,
            url: \Illuminate\Support\Facades\Storage::disk('public')->url($attachment->path)
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'path' => $this->path,
            'url' => $this->url,
        ];
    }
}