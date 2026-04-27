<?php

namespace App\Repositories;

use App\Models\Staff;
use App\Repositories\Contracts\StaffRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StaffRepository implements StaffRepositoryInterface
{
    public function __construct(private Staff $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Staff
    {
        return $this->model->create($data);
    }

    public function update(Staff $staff, array $data): bool
    {
        return $staff->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Staff
    {
        return $this->model->find($id);
    }
}