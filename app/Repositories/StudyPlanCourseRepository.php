<?php

namespace App\Repositories;

use App\Models\StudyPlanCourse;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\StudyPlanCourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StudyPlanCourseRepository implements StudyPlanCourseRepositoryInterface
{
    public function __construct(
        private StudyPlanCourse $model
    ) {}

    public function getAllPlanCourses(int $departmentId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with(['course.universalCourse'])
            ->where('department_id', $departmentId)
            ->orderBy('year')
            ->orderBy('semester')
            ->paginate($perPage);
    }

    public function getYearCourses(int $departmentId,int $year): Collection
    {
        return $this->model->with(['course.universalCourse'])
            ->where('department_id', $departmentId)
            ->where('year', $year)
            ->orderBy('semester')
            ->get();
    }

    public function getCourseDetails(int $departmentId,int $courseId): ?StudyPlanCourse
    {

        return $this->model->with(['course.universalCourse'])
            ->where('department_id', $departmentId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function searchCourses(int $departmentId,string $search): Collection
    {
        return $this->model->with(['course.universalCourse'])
            ->where('department_id', $departmentId)
            ->whereHas('course', function ($query) use ($search) {
                $query->where('code', 'LIKE', "%{$search}%")
                    ->orWhereHas('universalCourse', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            })->get();
    }

    public function getSemesterCourses(int $departmentId, int $year, int $semester): Collection
    {
        return $this->model
            ->with(['course.universalCourse'])
            ->where('department_id', $departmentId)
            ->where('year', $year)
            ->where('semester', $semester)
            ->get();
    }

    // public function getPassedCourses(int $studentId): Collection
    // {
    //     return $this->model
    //         ->with(['course.universalCourse'])
    //         ->where([
    //             'student_id' => $studentId,
    //             'status' => 'passed'
    //         ])->get();
    // }


}
