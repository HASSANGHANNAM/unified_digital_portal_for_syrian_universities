<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\StudyPlanCourse;

interface StudyPlanCourseRepositoryInterface
{
    public function getAllPlanCourses(int $departmentId): Collection;

    public function getYearCourses(int $departmentId,int $year): Collection;

    public function getCourseDetails(int $departmentId,int $courseId): ?StudyPlanCourse;

    public function searchCourses(int $departmentId,string $search): Collection;

    public function getSemesterCourses(int $departmentId,int $year,int $semester): Collection;



}
