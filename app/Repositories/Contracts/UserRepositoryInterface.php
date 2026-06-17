<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Ramsey\Collection\Collection;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function update(User $user, array $data): bool;
    public function all(): Collection;
    public function findByEmail(string $email): ?User;
    public function findByUserName(string $username): ?User;
    public function findById(int $id): ?User;
    public function assignRole(User $user, string $roleName): void;
    public function getUsersWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator;
    public function getProfile(User $user): array;
    public function findByPersonId(int $personId): ?User;
}
