<?php

namespace App\Services;

use App\DTOs\AllGradesDTO;
use App\Imports\StudentMarksImport;
use Illuminate\Http\UploadedFile;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\StudentCoursePartRepositoryInterface;
use App\Repositories\Contracts\StudentCourseRepositoryInterface;
use App\Repositories\Contracts\StudyPlanCourseRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\CoursePartRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\RequestRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;


class GradeService
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private StudentCoursePartRepositoryInterface $studentCoursePartRepositoryInterface,
        private StudentCourseRepositoryInterface $studentCourseRepositoryInterface,
        private StudyPlanCourseRepositoryInterface $studyPlanCourseRepositoryInterface,
        private StudentRepositoryInterface $studentRepositoryInterface,
        private CourseRepositoryInterface $courseRepository,
        private CoursePartRepositoryInterface $coursePartRepositoryInterface,
        private RequestRepository $requestRepository
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

    public function addGrade(int $courseId, string $academicYear, int $semester, UploadedFile $file): array
    {

        $user = Auth::user();

        $permission = $this->courseRepository->hasCourseAccess($user, $courseId);
        if (!$permission['status']) {

            return [
                'data' => [],
                'message' => $permission['message'],
                'code' => $permission['code'],
            ];
        }
        $course = Course::find($courseId);

        if (!$course) {
            return [
                'data' => [],
                'message' => 'Course not found.',
                'code' => 404,
            ];
        }
        $import = new StudentMarksImport($courseId, $academicYear, $semester);
        try {
            Excel::import($import, $file);
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

        return [
            'data' => $import->getReport(),
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
                'code' => 200,
            ];
        }

        $total = $grades->sum('credits');
        $status = $total >= 60 ? 'passed' : 'failed';

        return [
            'data' => [
                'course_id'       => $courseId,
                'course_name'     => optional($studentCourse->course->universalCourse)->name,
                'code'            => $studentCourse->course->code,
                'credits'         => $studentCourse->course->credits, // أو $studentCourse->credits حسب المطلوب
                'department_name' => optional($studentCourse->course->department)->name,
                'total_grade'     => $total,
                'status'          => $status,
                'parts' => $grades->map(function ($grade) {
                    return [
                        // 'id' => $grade->id,
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
        $perPage = request('per_page', 10);

        $filters = [
            'status' => request('status'),
            'course_name' => request('course_name'),
            'semester' => request('semester'),
            'academic_year' => request('academic_year'),
        ];

        $courses = $this->studentCourseRepositoryInterface
            ->getStudentCoursesWithGrades($user->id, $perPage, $filters);

        if ($courses->isEmpty()) {
            return [
                'data' => [],
                'message' => 'No grades found.',
                'code' => 200,
            ];
        }

        $data = collect($courses->items())->map(function ($studentCourse) {

            $publishedGrades = collect($studentCourse->parts)
                ->where('published', true);

            $total = $publishedGrades->sum('credits');

            return [
                'course_id'       => $studentCourse->course_id,
                'course_name'     => optional($studentCourse->course->universalCourse)->name,
                'code'            => $studentCourse->course->code,
                'credits'         => $studentCourse->course->credits,
                'department_name' => $studentCourse->course->department?->name,
                'academic_year'   => $studentCourse->academic_year,
                'semester'        => $studentCourse->semester,
                'total'           => $total,
                'status'          => $total >= 60 ? 'passed' : 'failed',
                'parts' => $publishedGrades->map(function ($part) {
                    return [
                        'part_name'  => optional($part->coursePart)->name,
                        'percentage' => optional($part->coursePart)->percentage,
                        'grade'      => $part->credits,
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

    // استعراض نتائج جميع الطلاب في مادة معينة مع حالة النجاح أو الرسوب لكل طالب(للامتحانات)
    public function getCourseGrades(User $user, int $courseId, string $academicYear, int $semester): array
    {
        if (!$user->hasRole('Examination')) {
            throw new \Exception('غير مصرح لك بالوصول');
        }
        $perPage = request()->input('per_page', 10);

        $studentCourses = $this->studentCourseRepositoryInterface
            ->getCourseGrades($courseId, $academicYear, $semester, $perPage);

        if ($studentCourses->isEmpty()) {
            return [
                'data'    => [],
                'message' => 'No grades found.',
                'code'    => 200,
            ];
        }

        $data = collect($studentCourses->items())->map(function ($studentCourse) {
            $publishedGrades = collect($studentCourse->parts)->where('published', 1);
            $total = $publishedGrades->sum('credits');

            return [
                'student_name'   => optional($studentCourse->student->person)->full_name,
                'student_number' => $studentCourse->student->student_id_number,
                'parts' => $publishedGrades->map(fn($part) => [
                    'id'         => $part->id,
                    'part_name'  => $part->coursePart->name,
                    'percentage' => $part->coursePart->percentage,
                    'grade'      => $part->credits,
                ])->values(),
                'total'  => $total,
                'status' => $total >= 60 ? 'passed' : 'failed',
            ];
        });

        return [
            'data' => [
                'course_id'     => $courseId,
                'course_name' => optional($studentCourses->first()?->course?->universalCourse)->name,
                'academic_year' => $academicYear,
                'semester'      => $semester,
                'students'      => $data,
                'meta' => [
                    'current_page' => $studentCourses->currentPage(),
                    'last_page'    => $studentCourses->lastPage(),
                    'per_page'     => $studentCourses->perPage(),
                    'total'        => $studentCourses->total(),
                ],
            ],
            'message' => 'Grades retrieved successfully.',
            'code'    => 200,
        ];
    }
    //تعديل علامة جزء معين من المادة لطالب معين (للامتحانات)للدكتور والمعيد والامتحانات
    public function updateGrade(User $user, int $studentCoursePartId, float $grade): array
    {
        $studentCoursePart = $this->studentCoursePartRepositoryInterface
            ->getPartById($studentCoursePartId);

        if (!$studentCoursePart) {
            return [
                'data'    => [],
                'message' => 'Grade record not found.',
                'code'    => 404,
            ];
        }

        $coursePermission = $this->courseRepository
            ->hasCourseAccess(
                $user,
                $studentCoursePart->studentCourse->course_id
            );

        if (!$coursePermission['status']) {
            return [
                'data'    => [],
                'message' => $coursePermission['message'],
                'code'    => $coursePermission['code'],
            ];
        }

        $requestPermission = $this->requestRepository
            ->canUpdateGrade(
                $user,
                $studentCoursePart
            );

        if (!$requestPermission['status']) {
            return [
                'data'    => [],
                'message' => $requestPermission['message'],
                'code'    => $requestPermission['code'],
            ];
        }

        $maxGrade = $studentCoursePart->coursePart->percentage;

        if ($grade > $maxGrade) {
            return [
                'data'    => [],
                'message' => "The maximum grade for this part is {$maxGrade}.",
                'code'    => 422,
            ];
        }

        $this->studentCoursePartRepositoryInterface
            ->updateGrade($studentCoursePartId, $grade);

        return [
            'data'    => [],
            'message' => 'Grade updated successfully.',
            'code'    => 200,
        ];
    }
    // إضافة علامات لطالب معين في مادة معينة (للامتحانات)
    public function addGradesforonestudent(User $user, int $courseId, string $academicYear, int $semester, array $request): array
    {
        $coursePermission = $this->courseRepository
            ->hasCourseAccess($user, $courseId);

        if (!$coursePermission['status']) {
            return [
                'data' => [],
                'message' => $coursePermission['message'],
                'code' => $coursePermission['code'],
            ];
        }

        $student = $this->studentRepositoryInterface
            ->findByStudentNumber($request['student_number']);

        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }
        $exists = $this->studyPlanCourseRepositoryInterface
            ->existsInStudyPlan(
                $student->department_id,
                $courseId
            );
        if (!$exists) {
            return [
                'data' => [],
                'message' => 'This course is not in the student study plan.',
                'code' => 422,
            ];
        }
        $studentCourse = $this->studentCourseRepositoryInterface
            ->findStudentCourseByStudentNumber(
                $request['student_number'],
                $courseId,
                $academicYear,
                $semester
            );
        if (!$studentCourse) {
            $studentCourse = $this->studentCourseRepositoryInterface
                ->create([
                    'student_id'     => $student->id,
                    'course_id'      => $courseId,
                    'academic_year'  => $academicYear,
                    'semester'       => $semester,
                    'credits'        => 0,
                    'status'         => 'in_progress',
                ]);
        }
        if ($studentCourse->parts()->exists()) {
            return [
                'data' => [],
                'message' => 'Grades have already been entered.',
                'code' => 422,
            ];
        }
        DB::beginTransaction();
        try {
            foreach ($request['parts'] as $part) {
                $coursePart = $this->coursePartRepositoryInterface
                    ->findByCourseAndPart(
                        $courseId,
                        $part['course_part_id']
                    );
                if (!$coursePart) {
                    throw new \Exception('Invalid course part.');
                }
                if ($part['grade'] > $coursePart->percentage) {
                    throw new \Exception(
                        "Maximum grade for {$coursePart->name} is {$coursePart->percentage}."
                    );
                }
                $this->studentCoursePartRepositoryInterface
                    ->create([
                        'student_course_id' => $studentCourse->id,
                        'course_part_id'    => $part['course_part_id'],
                        'credits'           => $part['grade'],
                        'published'         => 1,
                    ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return [
            'data' => [],
            'message' => 'Grades added successfully.',
            'code' => 201,
        ];
    }

    public function getUnpublishedMarks(User $user, int $courseId): array
    {
        if (!$user->hasRole('Examination')) {
            throw new \Exception('غير مصرح لك بالوصول');
        }
        $perPage = request('per_page', 10);
        $filters = [
            'course_name'    => request('course_name'),
            'semester'       => request('semester'),
            'academic_year'  => request('academic_year'),
        ];
        $courses = $this->studentCourseRepositoryInterface
            ->getUnpublishedMarks($courseId, $perPage, $filters);

        if ($courses->isEmpty()) {
            return [
                'data' => [],
                'message' => 'No unpublished marks found.',
                'code' => 404,
            ];
        }
        $data = collect($courses->items())->map(function ($studentCourse) {
            return [
                'student_course_id' => $studentCourse->id,
                'course_name' => optional($studentCourse->course->universalCourse)->name,
                'student_name' => optional($studentCourse->student->person)->full_name,
                'total' => $studentCourse->parts->sum('credits'),
                'parts' => $studentCourse->parts->map(function ($part) {
                    return [
                        'part_name' => optional($part->coursePart)->name,
                        'percentage' => optional($part->coursePart)->percentage,
                        'grade' => $part->credits,
                    ];
                })->values()

            ];
        });
        return [
            'data' => [
                'courses' => $data,
                'meta' => [
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'per_page' => $courses->perPage(),
                    'total' => $courses->total(),
                ]
            ],
            'message' => 'Unpublished marks retrieved successfully.',
            'code' => 200,
        ];
    }

    public function publishMarks(User $user, int $courseId): array
    {
        if (!$user->hasRole('Examination')) {
            throw new \Exception('غير مصرح لك بالوصول');
        }
        $filters = [
            'course_name'   => request('course_name'),
            'semester'      => request('semester'),
            'academic_year' => request('academic_year'),
        ];
        $count = $this->studentCourseRepositoryInterface
            ->publishMarks($courseId, $filters);
        return [
            'data' => [
                'updated_rows' => $count
            ],
            'message' => 'Marks published successfully.',
            'code' => 200,
        ];
    }
}
