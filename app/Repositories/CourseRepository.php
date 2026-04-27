<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function __construct(private Course $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Course
    {
        return $this->model->create($data);
    }

    public function update(Course $course, array $data): bool
    {
        return $course->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Course
    {
        return $this->model->find($id);
    }
}