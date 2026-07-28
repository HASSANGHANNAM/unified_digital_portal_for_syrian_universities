<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Course;
    public function update(Course $course, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Course;
    public function hasCourseAccess(User $user, int $courseId): array;
    public function getCourseWithParts(int $courseId, int $perPage = 15);
}
