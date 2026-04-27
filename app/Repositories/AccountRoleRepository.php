<?php

namespace App\Repositories;

use App\Models\AccountRole;
use App\Repositories\Contracts\AccountRoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AccountRoleRepository implements AccountRoleRepositoryInterface
{
    public function __construct(private AccountRole $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): AccountRole
    {
        return $this->model->create($data);
    }

    public function update(AccountRole $accountRole, array $data): bool
    {
        return $accountRole->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?AccountRole
    {
        return $this->model->find($id);
    }
}