<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\StudyPlanCourseRepositoryInterface;
use App\Repositories\Contracts\StudentCourseRepositoryInterface;
use App\Models\Student;

class StudyPlanService
{
    public function __construct(
        private StudyPlanCourseRepositoryInterface $studyPlanCourseRepositoryInterface,
        private StudentCourseRepositoryInterface $studentCourseRepositoryInterface
    ) {}
    //الخطة الدراسية للطالب
    public function getStudyPlan(): array
    {
        $user = Auth::user();
        $student = Student::where('person_id',$user->person_id)->first();
        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $courses = $this->studyPlanCourseRepositoryInterface->getAllPlanCourses($student->department_id);

        $data = $courses->map(function ($course) {
            return [
                'course_id' => $course->course_id,
                'course_name' => $course->course->universalCourse->name,
                'course_code' => $course->course->code,
                'credits' => $course->course->credits,
                'year' => $course->year,
                'semester' => $course->semester,
            ];
        });
        return [
            'data' => $data,
            'message' => 'Study plan retrieved successfully.',
            'code' => 200,
        ];
    }
    //مواد سنة معينة
    public function getYearCourses(int $year): array
    {
        $user = Auth::user();
        $student = Student::where('person_id',$user->person_id)->first();
        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $courses = $this->studyPlanCourseRepositoryInterface
            ->getYearCourses($student->department_id,$year);

        if ($courses->isEmpty()) {
            return [
                'data' => [],
                'message' => "No courses found for year {$year} in this department.",
                'code' => 404,
            ];
        }

        $data = $courses->map(function ($course) {
            return [
                'course_id' => $course->course_id,
                'course_name' => $course->course->universalCourse->name,
                'course_code' => $course->course->code,
                'credits' => $course->course->credits,
                'semester' => $course->semester,
            ];
        });
        return [
            'data' => $data,
            'message' => 'Courses retrieved successfully.',
            'code' => 200,
        ];
    }

    //تفاصيل المادة
    public function getCourseDetails(int $courseId): array
    {
        $user = Auth::user();
        $student = Student::where('person_id',$user->person_id)->first();
        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $course = $this->studyPlanCourseRepositoryInterface->getCourseDetails($student->department_id,$courseId);

        if (!$course) {
            return [
                'data' => [],
                'message' => 'Course not found.',
                'code' => 404,
            ];
        }

        return [
            'data' => [
                'id' => $course->course_id,
                'name' => $course->course->universalCourse->name,
                'code' => $course->course->code,
                'credits' => $course->course->credits,
                'year' => $course->year,
                'semester' => $course->semester,
            ],
            'message' => 'Course details retrieved successfully.',
            'code' => 200,
        ];
    }

    //بحث عن مادة
    public function searchCourses(string $search): array
    {
        if (!$search|| trim($search) === null) {
          return [
                'data' => [],
                'message' => 'Search query cannot be empty.',
                'code' => 400,
            ];
        }

        $user = Auth::user();
        $student = Student::where('person_id', $user->person_id)->first();
        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $courses = $this->studyPlanCourseRepositoryInterface->searchCourses($student->department_id, $search);
        if ($courses->isEmpty()) {
            return [
                'data' => [],
                'message' => 'No courses found matching your search.',
                'code' => 404,
            ];
        }

        $data = $courses->map(function ($course) {
            return [
                'course_id'   => $course->course_id,
                'course_name' => $course->course?->universalCourse?->name ?? null,
                'course_code' => $course->course?->code ?? null,
                'credits'     => $course->course?->credits ?? 0,
                'year'        => $course->year,
                'semester'    => $course->semester,
            ];
        });

        return [
            'data' => $data,
            'message' => 'Search completed successfully.',
            'code' => 200,
        ];
    }
    //مواد الفصل الحالي
    public function getCurrentSemesterCourses(): array
    {
        $student = $this->getCurrentStudent();

        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $courses = $this->studyPlanCourseRepositoryInterface
          ->getSemesterCourses($student->department_id,$student->current_year,$student->current_semester);

        $data = $courses->map(function ($course) {
            return [
                'course_id' => $course->course_id,
                'course_name' => $course->course->universalCourse->name,
                'course_code' => $course->course->code,
                'credits' => $course->course->credits,
            ];
        });
        return [
            'data' => $data,
            'message' => 'Current semester courses retrieved successfully.',
            'code' => 200,
        ];
    }

    //المواد التي تم اجتيازها
    public function getCompletedCourses(): array
    {
        $student = $this->getCurrentStudent();

        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $courses = $this->studentCourseRepositoryInterface->getPassedCourses($student->id);
        $data = $courses->map(function ($course) {
            return [
                'course_id' => $course->course_id,
                'course_name' => $course->course->universalCourse->name,
                'status' => $course->status,
            ];
        });
        return [
            'data' => $data,
            'message' => 'Completed courses retrieved successfully.',
            'code' => 200,
        ];
    }

    //المواد المتبقية
    public function getRemainingCourses(): array
    {
        $student = $this->getCurrentStudent();

        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $planCourses = $this->studyPlanCourseRepositoryInterface->getAllPlanCourses($student->department_id);

        $passedIds = $this->studentCourseRepositoryInterface->getPassedCourses($student->id)->pluck('course_id')->toArray();

        $remaining = $planCourses->whereNotIn('course_id', $passedIds);

        $data = $remaining->map(function ($course) {
            return [
                'course_id' => $course->course_id,
                'course_name' => $course->course->universalCourse->name,
                'year' => $course->year,
                'semester' => $course->semester,
            ];
        });

        return [
            'data' => $data,
            'message' => 'Remaining courses retrieved successfully.',
            'code' => 200,
        ];
    }

    //التقدم الأكاديمي
    public function getAcademicProgress(): array
    {
        $student = $this->getCurrentStudent();

        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $totalCourses = $this->studyPlanCourseRepositoryInterface->getAllPlanCourses($student->department_id)->count();

        $passedCourses = $this->studentCourseRepositoryInterface->getPassedCourses($student->id)->count();

        $progress = $totalCourses > 0
        ? round(($passedCourses / $totalCourses) * 100, 2)
        : 0;

        return [
            'data' => [
                'total_courses' => $totalCourses,
                'passed_courses' => $passedCourses,
                'remaining_courses' => $totalCourses - $passedCourses,
                'progress_percentage' => $progress,
            ],
            'message' => 'Academic progress retrieved successfully.',
            'code' => 200,
        ];
    }

    //المواد للسنة الحالية
    public function getCurrentYearCourses(): array
    {
        $student = $this->getCurrentStudent();

        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }
        $courses = $this->studyPlanCourseRepositoryInterface->getYearCourses($student->department_id,$student->current_year);

        $data = $courses->map(function ($course) {
            return [
                'course_id' => $course->course_id,
                'course_name' => $course->course->universalCourse->name,
                'course_code' => $course->course->code,
                'credits' => $course->course->credits,
                'semester' => $course->semester,
            ];
        });

        return [
            'data' => $data,
            'message' => 'Current year courses retrieved successfully.',
            'code' => 200,
        ];
    }

    private function getCurrentStudent(): ?Student
    {
        return Student::where(
            'person_id',
            Auth::user()->person_id
        )->first();
    }
}
