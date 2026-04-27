<?php

namespace App\Repositories;

use App\Models\UniversalCourse;
use App\Repositories\Contracts\UniversalCourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UniversalCourseRepository implements UniversalCourseRepositoryInterface
{
    public function __construct(private UniversalCourse $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): UniversalCourse
    {
        return $this->model->create($data);
    }

    public function update(UniversalCourse $universalCourse, array $data): bool
    {
        return $universalCourse->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?UniversalCourse
    {
        return $this->model->find($id);
    }
}