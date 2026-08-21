<?php

namespace App\Repositories;

use App\Models\Department;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function __construct(private Department $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Department
    {
        return $this->model->create($data);
    }

    public function update(Department $department, array $data): bool
    {
        return $department->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Department
    {
        return $this->model->find($id);
    }
    public function getDepartments(array $filters): Collection
    {
        $query = $this->model->newQuery()
            ->orderBy('name', 'asc');
        if (!empty($filters['college_id'])) {
            $query->where('college_id', $filters['college_id']);
        }

        return $query->get(['id', 'name']);
    }
    public function getDepartmentsWithHead(array $filters): Collection
    {
        $query = $this->model->newQuery()
            ->with(['head.doctor.person'])
            ->orderBy('name', 'asc');

        if (!empty($filters['college_id'])) {
            $query->where('college_id', $filters['college_id']);
        }

        return $query->get(['id', 'name']);
    }
}
