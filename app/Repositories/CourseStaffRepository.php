<?php

namespace App\Repositories;

use App\Models\CourseStaff;
use App\Repositories\Contracts\CourseStaffRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CourseStaffRepository implements CourseStaffRepositoryInterface
{
    public function __construct(private CourseStaff $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): CourseStaff
    {
        return $this->model->create($data);
    }

    public function update(CourseStaff $courseStaff, array $data): bool
    {
        return $courseStaff->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?CourseStaff
    {
        return $this->model->find($id);
    }
}