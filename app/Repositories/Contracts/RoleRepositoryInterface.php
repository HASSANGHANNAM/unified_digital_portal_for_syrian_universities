<?php

namespace App\Repositories\Contracts;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Role;
    public function update(Role $role, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Role;
}