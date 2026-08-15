<?php

namespace App\Repositories\Contracts;

use App\Models\StudentCourse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface StudentCourseRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): StudentCourse;
    public function update(StudentCourse $studentCourse, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?StudentCourse;
    public function getCourseGrades(int $courseId, string $academicYear, int $semester, int $perPage = 10);
    public function findStudentCourseByStudentNumber(string $studentNumber, int $courseId, string $academicYear, int $semester);
    public function findStudentCourse(int $studentId, int $courseId);
    public function getStudentCoursesWithGrades(int $userId, int $perPage, array $filters = []);
    public function getStudentCoursesWithGradesArray(int $studentId): array;
    public function getUnpublishedMarks(int $courseId, int $perPage, array $filters = []);
    public function publishMarks(int $courseId, array $filters = []);
    public function getCourseStudents(int $collegeId, int $courseId, array $filters, int $perPage = 15): LengthAwarePaginator;
    public function getCoursePartsWithStudentParts(int $studentCourseId): array;
}
