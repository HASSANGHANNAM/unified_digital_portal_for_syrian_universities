<?php

namespace App\Services;

use App\DTOs\AllGradesDTO;
use App\Imports\StudentMarksImport;
use App\Models\Course;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\StudentCoursePartRepositoryInterface;
use App\Repositories\Contracts\StudentCourseRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class GradeService
{
     public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private StudentCoursePartRepositoryInterface $studentCoursePartRepositoryInterface,
        private StudentCourseRepositoryInterface $studentCourseRepositoryInterface,
    ) {}

    public function getAllGrades(array $data): array
    {
        $message = 'عرض علامات جميع الطلاب (لشؤون الامتحانات).';
        $code = 200;
        // use App\DTOs\AllGradesDTO;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function addGrade(array $data): array
    {
        $course = Course::find($data['course_id']);
        if (!$course) {
            return [
                'data' => [],
                'message' => 'Course not found.',
                'code' => 404,
            ];
        }
        $import = new StudentMarksImport($course->id);

        try {
            Excel::import($import, $data['file']);
        } catch (\Maatwebsite\Excel\Exceptions\ValidationException $exception) {
            return [
                'data' => [
                    'errors' => $exception->errors(),
                ],
                'message' => 'Excel validation failed.',
                'code' => 422,
            ];
        } catch (\Throwable $exception) {
            return [
                'data' => [
                    'exception' => $exception->getMessage(),
                ],
                'message' => 'Failed importing grades.',
                'code' => 500,
            ];
        }

        $report = $import->getReport();
        return [
            'data' => $report,
            'message' => 'تم رفع العلامات بنجاح.',
            'code' => 200,
        ];
    }

    public function getGradeAppeals(array $data): array
    {
        $message = 'قائمة الاعتراضات على العلامات.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function processAppeal(array $data, int $appealId): array
    {
        $message = 'معالجة اعتراض (تعديل العلامة أو رفض الاعتراض).';
        $code = 200;
        $data = array_merge($data, ["appealId" => $appealId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }
    // استعراض نتيجة مادة معينة للطالب مع حالة النجاح أو الرسوب
    public function getgrade(int $courseId): array
    {
        $user = Auth::user();
        $studentCourse = $this->studentCourseRepositoryInterface->findStudentCourse($user->id, $courseId);
        if (!$studentCourse) {
            return [
                'data' => [],
                'message' => 'Course not found for this student.',
                'code' => 404,
            ];
        }
        $grades = $this->studentCoursePartRepositoryInterface->getStudentCourseGrades($studentCourse->id);
        if ($grades->isEmpty()) {

            return [
                'data' => [],
                'message' => 'No grades found for this course.',
                'code' => 404,
            ];
        }

        $total = $grades->sum('credits');
        $status = $total >= 60 ? 'passed' : 'failed';

        return [
            'data' => [
                'course_id' => $courseId,
                'course_name' => $studentCourse->course->name,
                'total_grade' => $total,
                'status' => $status,
                'parts' => $grades->map(function ($grade) {
                    return [
                        'id' => $grade->id,
                        'part_name' => $grade->coursePart->name,
                        'percentage' => $grade->coursePart->percentage,
                        'grade' => $grade->credits,
                    ];
                }),
            ],
            'message' => 'Grades retrieved successfully.',
            'code' => 200,
        ];
    }

    // استعراض نتائج جميع المواد للطالب مع حالة النجاح أو الرسوب لكل مادة
    public function getAllMyGrades(): array
    {
        $user = Auth::user();

        $perPage = request()->input('per_page', 10);
        $courses = $this->studentCourseRepositoryInterface->getStudentCoursesWithGrades($user->id, $perPage);

        if ($courses->isEmpty()) {
            return [
                'data' => [],
                'message' => 'No grades found.',
                'code' => 404,
            ];
        }

        $data = collect($courses->items())->map(function ($studentCourse) {
            $publishedGrades = collect($studentCourse->parts)->where('published', 1);
            $total = $publishedGrades->sum('credits');

            return [
                'course_id' => $studentCourse->course_id,
                'course_name' => $studentCourse->course->name,
                'total' => $total,
                'status' => $total >= 60 ? 'passed' : 'failed',
                'parts' => $publishedGrades->map(function ($part) {
                    return [
                        'name' => $part->coursePart->name,
                        'grade' => $part->credits,
                    ];
                })->values(),
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
            'message' => 'Grades retrieved successfully.',
            'code' => 200,
        ];
    }

}

