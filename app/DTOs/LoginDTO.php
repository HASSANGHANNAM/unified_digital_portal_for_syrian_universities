<?php

namespace App\DTOs;

class LoginDTO
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly int $expiresIn,
        public readonly array $roles,
        public readonly UserDTO $user
    ) {}

    public static function fromServiceData(array $data, $user): self
    {
        return new self(
            accessToken: $data['tokens']['access_token'],
            refreshToken: $data['tokens']['refresh_token'],
            expiresIn: $data['tokens']['expires_in'],
            roles: $data['roles']->toArray(),
            user: UserDTO::fromModel($user)
        );
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_in' => $this->expiresIn,
            'roles' => $this->roles,
        ];
    }
}
