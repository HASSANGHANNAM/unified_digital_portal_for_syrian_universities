<?php

namespace App\Services;

use App\Models\Course;
use App\Models\DepartmentHead;
use App\Models\Doctor;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Staff;
use Exception;

class AffairsService
{
    public function __construct(
        protected CourseRepositoryInterface $courseRepository
    ) {}

    //بجيب كل مواد الكلية (امتحانات)
    public function getCollegeCourses(): array
    {
        $user = Auth::user();
        if (!$user->hasRole('Examination')) {
            throw new \Exception('غير مصرح لك بالوصول');
        }
        $staff = Staff::with('department')
            ->where('person_id', $user->person_id)
            ->first();
        if (!$staff) {
            return [
                'data' => [],
                'message' => 'Staff not found.',
                'code' => 404,
            ];
        }
        if (!$staff->department) {
            return [
                'data' => [],
                'message' => 'Department not found.',
                'code' => 404,
            ];
        }

        $courses = $this->courseRepository->getCollegeCourses($staff->department->college_id);

        if ($courses->isEmpty()) {
            return [
                'data' => [],
                'message' => 'No courses found.',
                'code' => 404,
            ];
        }

        $data = $courses->map(function ($course) {
            return [
                'id'   => $course->id,
                'name' => $course->universalCourse->name,
                'code' => $course->code,
            ];
        });
        return [
            'data' => $data,
            'message' => 'Courses retrieved successfully.',
            'code' => 200,
        ];
    }
    public function getDepartmentCourses($departmentId): array
    {
        $user = Auth::user();
        if (!$user->hasRole('HeadOfDepartment')) {
            throw new \Exception('غير مصرح لك بالوصول');
        }
        $courses = Course::with('universalCourse')
            ->where('department_id', $departmentId)
            ->get();

        if ($courses->isEmpty()) {
            return [
                'data' => [],
                'message' => 'No courses found.',
                'code' => 404,
            ];
        }

        $data = $courses->map(function ($course) {
            return [
                'id'   => $course->id,
                'name' => $course->universalCourse->name,
                'code' => $course->code,
            ];
        });
        return [
            'data' => $data,
            'message' => 'Courses retrieved successfully.',
            'code' => 200,
        ];
    }
}
