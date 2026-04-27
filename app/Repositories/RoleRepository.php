<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(private Role $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    public function update(Role $role, array $data): bool
    {
        return $role->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Role
    {
        return $this->model->find($id);
    }
}