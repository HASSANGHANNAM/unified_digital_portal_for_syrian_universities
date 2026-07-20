<?php

namespace App\DTOs;

use App\Models\RequestType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RequestTypeDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly bool $is_available,
        public readonly bool $requires_course,
    ) {}

    public static function fromModel(RequestType $model): self
    {
        $availability = $model->availability->first() ?? null;
        return new self(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            is_available: (bool) ($availability->is_available ?? false),
            requires_course: (bool) ($model->requires_course ?? false)
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'requires_course' => $this->requires_course,
            'is_available' => $this->is_available,
        ];
    }

    public static function fromServiceData(Collection|LengthAwarePaginator $types): array
    {
        $items = $types instanceof LengthAwarePaginator ? collect($types->items()) : collect($types);
        $mapped = $items->map(fn($t) => self::fromModel($t)->toArray())->toArray();
        usort($mapped, fn($a, $b) => (int) $b['is_available'] <=> (int) $a['is_available']);

        $data = [
            'requests_types' => $mapped,
        ];

        if ($types instanceof LengthAwarePaginator) {
            $data['meta'] = [
                'current_page' => $types->currentPage(),
                'last_page' => $types->lastPage(),
                'per_page' => $types->perPage(),
                'total' => $types->total(),
            ];
        }

        return $data;
    }
}
