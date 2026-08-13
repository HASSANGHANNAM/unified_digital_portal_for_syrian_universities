<?php

namespace App\Repositories;

use App\Models\CollegeFile;
use App\Repositories\Contracts\CollegeFileRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CollegeFileRepository implements CollegeFileRepositoryInterface
{
    public function create(array $data): CollegeFile
    {
        return CollegeFile::create($data);
    }

    public function getFilesForStudent(
        int $collegeId,
        int $enrollmentYear,
        int $currentYear,
        int $studentYear,
        int $studentSemester,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? $perPage;

        $query = CollegeFile::where('college_id', $collegeId)
            ->where('upload_year', '>=', $enrollmentYear)
            ->where(function ($query) use ($studentYear, $studentSemester) {
                $query->where('student_year', '<', $studentYear)
                    ->orWhere(function ($q) use ($studentYear, $studentSemester) {
                        $q->where('student_year', '=', $studentYear)
                            ->where('student_semester', '<=', $studentSemester);
                    });
            });
        if (!empty($filters['student_year'])) {
            $query->where('student_year', $filters['student_year']);
        }

        if (!empty($filters['upload_year'])) {
            $query->where('upload_year', $filters['upload_year']);
        }

        if (!empty($filters['student_semester'])) {
            $query->where('student_semester', $filters['student_semester']);
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        $query->orderBy('upload_year', 'desc')
            ->orderBy('student_year', 'desc')
            ->orderBy('student_semester', 'desc');

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
    public function getFilesForStaff(
        int $collegeId,
        array $filters = [],
        int $perPage = 15
    ): LengthAwarePaginator {
        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? $perPage;

        $query = CollegeFile::where('college_id', $collegeId);
        if (!empty($filters['student_year'])) {
            $query->where('student_year', $filters['student_year']);
        }

        if (!empty($filters['upload_year'])) {
            $query->where('upload_year', $filters['upload_year']);
        }

        if (!empty($filters['student_semester'])) {
            $query->where('student_semester', $filters['student_semester']);
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        $query->orderBy('upload_year', 'desc')
            ->orderBy('student_year', 'desc')
            ->orderBy('student_semester', 'desc');

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
