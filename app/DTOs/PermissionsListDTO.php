<?php

namespace App\DTOs;

class PermissionsListDTO
{
    public function __construct(public readonly array $permissions) {}

    public static function fromArray(array $permissions): self
    {
        return new self(array_values($permissions));
    }

    public function toArray(): array
    {
        return ['permissions' => $this->permissions];
    }
}
