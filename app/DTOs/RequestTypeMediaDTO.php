<?php

namespace App\DTOs;

use App\Models\RequestTypeMedia;
use Illuminate\Database\Eloquent\Collection;

class RequestTypeMediaDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $type,
        public readonly int $request_type_id
    ) {}

    public static function fromModel(RequestTypeMedia $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            type: $model->type,
            request_type_id: $model->request_type_id,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'request_type_id' => $this->request_type_id,
        ];
    }

    public static function fromServiceData(Collection $media): array
    {
        return [
            'request_type_media' => $media->map(fn(RequestTypeMedia $item) => self::fromModel($item)->toArray())->toArray(),
        ];
    }
}
