<?php

namespace App\Repositories;

use App\Models\StudentCoursePart;
use App\Repositories\Contracts\StudentCoursePartRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StudentCoursePartRepository implements StudentCoursePartRepositoryInterface
{
    public function __construct(private StudentCoursePart $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): StudentCoursePart
    {
        return $this->model->create($data);
    }

    public function update(StudentCoursePart $studentCoursePart, array $data): bool
    {
        return $studentCoursePart->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?StudentCoursePart
    {
        return $this->model->find($id);
    }

    public function getStudentCourseGrades(int $studentCourseId)
    {
        return $this->model->with('coursePart')->where('student_course_id', $studentCourseId)->where('published', 1)->get();
    }
}
