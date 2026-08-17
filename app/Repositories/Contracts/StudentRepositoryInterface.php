<?php

namespace App\Repositories\Contracts;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

interface StudentRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Student;
    public function update(Student $student, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Student;
    public function findByStudentNumber(string $studentNumber);
    public function getPendingStudents();
    public function getStudentsByCollege(int $collegeId, int $perPage = 15, int $page = 1);
    public function importStudents($file,int $universityId,int $collegeId): array;

}
