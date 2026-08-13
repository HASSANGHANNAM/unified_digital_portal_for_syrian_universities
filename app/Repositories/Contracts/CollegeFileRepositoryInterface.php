<?php

namespace App\Repositories\Contracts;

use App\Models\CollegeFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CollegeFileRepositoryInterface
{
    public function create(array $data): CollegeFile;
    public function getFilesForStudent(
        int $collegeId,
        int $enrollmentYear,
        int $currentYear,
        int $student_year,
        int $student_semester,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator;
    public function getFilesForStaff(
        int $collegeId,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator;
}
