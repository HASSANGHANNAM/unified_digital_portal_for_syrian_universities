<?php

namespace App\Repositories\Contracts;

use App\Models\StudentCoursePart;
use Illuminate\Database\Eloquent\Collection;

interface StudentCoursePartRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): StudentCoursePart;
    public function update(StudentCoursePart $studentCoursePart, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?StudentCoursePart;
    public function getPartById(int $id);
    public function updateGrade(int $studentCoursePartId, float $grade): bool;
    public function getStudentCourseGrades(int $studentCourseId);
}
