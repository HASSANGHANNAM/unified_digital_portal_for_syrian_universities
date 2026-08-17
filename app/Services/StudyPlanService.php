<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\StudyPlanCourseRepositoryInterface;
use App\Repositories\Contracts\StudentCourseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
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

        $student = Student::where('person_id', $user->person_id)->first();

        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $perPage = request('per_page', 10);

        $filters = [
            'course_name'   => request('course_name'),
            'year'          => request('year'),
            'semester'      => request('semester'),
        ];

        $courses = $this->studyPlanCourseRepositoryInterface
            ->getAllPlanCourses(
                $student->department_id,
                $perPage,
                $filters
            );

        $data = collect($courses->items())->map(function ($course) {
            return [
                'course_id' => $course->course_id,
                'course_name' => $course->course->universalCourse->name ?? '',
                'course_code' => $course->course->code ?? '',
                'credits' => $course->course->credits ?? 0,
                'year' => $course->year,
                'semester' => $course->semester,
            ];
        });

        return [
            'data' => [
                'courses' => $data,
                'meta' => [
                    'current_page' => $courses->currentPage(),
                    'last_page'    => $courses->lastPage(),
                    'per_page'     => $courses->perPage(),
                    'total'        => $courses->total(),
                ]
            ],
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

          if ($courses->isEmpty()) {
            return [
                'data' => [],
                'message' => 'No courses found for the current year and semester',
                'code' => 200,
            ];
         }

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

    public function getCompletedCourses(): array
    {
        $student = $this->getCurrentStudent();

        if (!$student) {
            return ['data' => [], 'message' => 'Student not found.', 'code' => 404];
        }

        $perPage = request()->input('per_page', 10);


        $paginatedCourses = \App\Models\StudentCourse::with(['course.universalCourse', 'course.department'])
            ->where([
                'student_id' => $student->id,
                'status'     => 'pass'
            ])
            ->paginate($perPage);


        $data = collect($paginatedCourses->items())->map(function ($course) {
            return [
                'course_id'       => $course->course_id,
                'course_name'     => $course->course->universalCourse->name ?? 'N/A',
                'course_code'     => $course->course->code ?? 'N/A',
                'department_name' => $course->course->department->name ?? 'N/A',
                'credits'         => $course->course->credits ?? 0,
                'status'          => $course->status,
            ];
        });

        return [
            'data' => [
                'courses' => $data,
                'meta' => [
                    'current_page' => $paginatedCourses->currentPage(),
                    'last_page'    => $paginatedCourses->lastPage(),
                    'per_page'     => $paginatedCourses->perPage(),
                    'total'        => $paginatedCourses->total(),
                ]
            ],
            'message' => 'Completed courses retrieved successfully.',
            'code' => 200,
        ];
    }
    public function getRemainingCourses(): array
    {
        $student = $this->getCurrentStudent();

        if (!$student) {
            return ['data' => [], 'message' => 'Student not found.', 'code' => 404];
        }

        $perPage = request()->input('per_page', 10);
        $passedIds = \App\Models\StudentCourse::where([
            'student_id' => $student->id,
            'status'     => 'pass'
        ])->pluck('course_id')->toArray();

        $paginatedRemaining = \App\Models\StudyPlanCourse::with(['course.universalCourse', 'department'])
            ->where('department_id', $student->department_id)
            ->whereNotIn('course_id', $passedIds)
            ->paginate($perPage);

        $data = collect($paginatedRemaining->items())->map(function ($course) {
            return [
                'course_id'       => $course->course_id,
                'course_name'     => $course->course->universalCourse->name ?? 'N/A',
                'course_code'     => $course->course->code ?? 'N/A',
                'department_name' => $course->department->name ?? 'N/A',
                'year'            => $course->year,
                'semester'        => $course->semester,
            ];
        });

        return [
            'data' => [
                'courses' => $data,
                'meta' => [
                    'current_page' => $paginatedRemaining->currentPage(),
                    'last_page'    => $paginatedRemaining->lastPage(),
                    'per_page'     => $paginatedRemaining->perPage(),
                    'total'        => $paginatedRemaining->total(),
                ]
            ],
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

        if ($courses->isEmpty()) {
            return [
                'data' => [],
                'message' => 'No courses found for the current year and semester.',
                'code' => 200,
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
