<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;
use Ramsey\Collection\Collection;

class UserRepository implements UserRepositoryInterface

{
    public function __construct(private User $user) {}

    public function create(array $data): User
    {
        return $this->user->create([
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'last_login' => $data['last_login'] ?? null,
            'new_password' => $data['new_password'] ?? null,
            'status' => $data['status'] ?? 'active',
            'password' => Hash::make($data['password']),
            'email_verified_at' => $data['email_verified_at'] ?? null,
            'person_id' => $data['person_id'] ?? null,
        ]);
    }
    public function all(): Collection
    {
        throw new \Exception('Not implemented');
    }
    public function findByEmail(string $Email): ?User
    {
        return $this->user->where('Email', $Email)->first();
    }

    public function findByUserName(string $username): ?User
    {
        return $this->user->where('username', $username)->first();
    }

    public function findById(int $id): ?User
    {
        return $this->user->find($id);
    }

    public function assignRole(User $user, string $roleName): void
    {
        $user->assignRole($roleName);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function getProfile(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'status' => $user->status,
            'last_login' => $user->last_login,
            'created_at' => $user->created_at,
        ];
    }
}
