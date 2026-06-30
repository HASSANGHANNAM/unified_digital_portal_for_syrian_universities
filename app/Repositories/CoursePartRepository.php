<?php

namespace App\Repositories;

use App\Models\CoursePart;
use App\Repositories\Contracts\CoursePartRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CoursePartRepository implements CoursePartRepositoryInterface
{
    public function __construct(private CoursePart $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): CoursePart
    {
        return $this->model->create($data);
    }

    public function update(CoursePart $coursePart, array $data): bool
    {
        return $coursePart->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?CoursePart
    {
        return $this->model->find($id);
    }
    
    public function findByCourseAndPart(int $courseId, int $coursePartId)
    {
        return $this->model
            ->where('id', $coursePartId)
            ->where('course_id', $courseId)
            ->first();
    }
}
