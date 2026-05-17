<?php

namespace App\DTOs;

use App\Models\User;

class AllGradesDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly string $email,
        public readonly string $status,
        public readonly ?string $fullName = null,
        public readonly ?string $phone = null,
        public readonly array $roles = []
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            username: $user->username,
            email: $user->email,
            status: $user->status,
            fullName: $user->person?->full_name,
            phone: $user->person?->phone,
            roles: $user->roles->pluck('name')->toArray()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'status' => $this->status,
            'full_name' => $this->fullName,
            'phone' => $this->phone,
            'roles' => $this->roles,
        ];
    }
}
