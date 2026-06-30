<?php

namespace App\Repositories\Contracts;

use App\Models\CoursePart;
use Illuminate\Database\Eloquent\Collection;

interface CoursePartRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): CoursePart;
    public function update(CoursePart $coursePart, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?CoursePart;
    public function findByCourseAndPart(int $courseId, int $coursePartId);
}
