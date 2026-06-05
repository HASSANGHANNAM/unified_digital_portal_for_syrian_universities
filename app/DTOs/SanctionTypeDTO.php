<?php

namespace App\DTOs;

use App\Models\SanctionType;

class SanctionTypeDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $reason,
        public int $years,
        public int $months,
        public int $days,
    ) {}

    public static function fromModel(SanctionType $model): self
    {
        return new self(
            (string) $model->id,
            $model->name ?? '',
            $model->reason ?? '',
            (int) ($model->years ?? 0),
            (int) ($model->months ?? 0),
            (int) ($model->days ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'reason' => $this->reason,
            'years' => $this->years,
            'months' => $this->months,
            'days' => $this->days,
        ];
    }
}
