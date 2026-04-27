<?php

namespace App\Repositories\Contracts;

use App\Models\StudentCourse;
use Illuminate\Database\Eloquent\Collection;

interface StudentCourseRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): StudentCourse;
    public function update(StudentCourse $studentCourse, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?StudentCourse;
}