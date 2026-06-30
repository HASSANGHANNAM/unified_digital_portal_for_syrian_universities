<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\StudyPlanCourse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface StudyPlanCourseRepositoryInterface
{
    public function getAllPlanCourses(int $departmentId, int $perPage = 10): LengthAwarePaginator;

    public function getYearCourses(int $departmentId,int $year): Collection;

    public function getCourseDetails(int $departmentId,int $courseId): ?StudyPlanCourse;

    public function searchCourses(int $departmentId,string $search): Collection;

    public function getSemesterCourses(int $departmentId,int $year,int $semester): Collection;

    public function getPassedCourses(int $studentId): Collection;
    
    public function existsInStudyPlan(int $departmentId,int $courseId): bool;



}
