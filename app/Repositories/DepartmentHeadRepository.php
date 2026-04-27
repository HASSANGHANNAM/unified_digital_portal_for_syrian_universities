<?php

namespace App\Repositories;

use App\Models\DepartmentHead;
use App\Repositories\Contracts\DepartmentHeadRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DepartmentHeadRepository implements DepartmentHeadRepositoryInterface
{
    public function __construct(private DepartmentHead $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): DepartmentHead
    {
        return $this->model->create($data);
    }

    public function update(DepartmentHead $departmentHead, array $data): bool
    {
        return $departmentHead->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?DepartmentHead
    {
        return $this->model->find($id);
    }
}