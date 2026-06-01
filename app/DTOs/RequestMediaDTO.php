<?php

namespace App\DTOs;

use App\Models\RequestMedia;
use Illuminate\Support\Facades\Storage;

class RequestMediaDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $type,
        public readonly string $url,
    ) {}

    public static function fromModel(RequestMedia $media): self
    {
        $url = '/api/V1/media/' . $media->id;
        return new self(
            (string) $media->id,
            (string) $media->name,
            (string) $media->type,
            $url,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'url' => $this->url,
        ];
    }
}
