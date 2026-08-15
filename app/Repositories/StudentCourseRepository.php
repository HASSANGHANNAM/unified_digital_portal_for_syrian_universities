<?php

namespace App\Repositories;

use App\Models\CourseStaff;
use App\Models\StudentCourse;
use App\Models\StudentCoursePart;
use App\Repositories\Contracts\StudentCourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentCourseRepository implements StudentCourseRepositoryInterface
{
    public function __construct(private StudentCourse $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): StudentCourse
    {
        return $this->model->create($data);
    }

    public function update(StudentCourse $studentCourse, array $data): bool
    {
        return $studentCourse->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?StudentCourse
    {
        return $this->model->find($id);
    }

    public function findStudentCourse(int $studentId, int $courseId)
    {
        return $this->model
            ->with('course')
            ->where('student_id', $studentId)
            ->where('id', $courseId)
            ->first();
    }

    public function getStudentCoursesWithGrades(int $userId, int $perPage, array $filters = [])
    {
        $query = StudentCourse::with([
            'course.universalCourse',
            'course.department',
            'parts.coursePart',
            'student.person.user',
        ])
            ->whereHas('student.person.user', function ($q) use ($userId) {
                $q->where('id', $userId);
            });

        // اسم المادة
        if (!empty($filters['course_name'])) {
            $query->whereHas('course.universalCourse', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['course_name'] . '%');
            });
        }

        // السنة
        if (!empty($filters['academic_year'])) {
            $query->where('academic_year', $filters['academic_year']);
        }

        // الفصل
        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        // النجاح والرسوب
        if (!empty($filters['status'])) {

            if ($filters['status'] == 'passed') {

                $query->whereHas('parts', function ($q) {
                    $q->where('published', 1);
                })
                    ->withSum([
                        'parts as total_grade' => function ($q) {
                            $q->where('published', 1);
                        }
                    ], 'credits')
                    ->having('total_grade', '>=', 60);
            } elseif ($filters['status'] == 'failed') {

                $query->whereHas('parts', function ($q) {
                    $q->where('published', 1);
                })
                    ->withSum([
                        'parts as total_grade' => function ($q) {
                            $q->where('published', 1);
                        }
                    ], 'credits')
                    ->having('total_grade', '<', 60);
            }
        }
        return $query->paginate($perPage);
    }
    public function getStudentCoursesWithGradesArray(int $studentId): array
    {
        $courses = $this->model->with([
            'course.universalCourse',
            'course.department',
            'parts.coursePart',
        ])
            ->where('student_id', $studentId)
            ->get();
        $result = [];
        foreach ($courses as $studentCourse) {
            $publishedParts = $studentCourse->parts->filter(function ($part) {
                return $part->published == 1;
            });
            $total = $publishedParts->sum('credits');
            $status = $total >= 60 ? 'passed' : 'failed';
            $result[] = [
                'course_name' => $studentCourse->course->universalCourse->name ?? $studentCourse->course->name ?? '',
                'code' => $studentCourse->course->code ?? '',
                'total' => $total,
                'status' => $status,
            ];
        }
        return ['courses' => $result];
    }
    public function getPassedCourses(int $studentId): Collection
    {
        return $this->model
            ->with([
                'course.universalCourse',
                'course.department',
            ])
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();
    }

    public function getCourseGrades(int $courseId, string $academicYear, int $semester, int $perPage = 10)
    {
        return $this->model
            ->with([
                'student.person',
                'course.universalCourse',
                'parts.coursePart',
            ])
            ->where('course_id', $courseId)
            ->where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->paginate($perPage);
    }

    public function findStudentCourseByStudentNumber(string $studentNumber, int $courseId, string $academicYear, int $semester)
    {
        return $this->model
            ->whereHas('student', function ($q) use ($studentNumber) {
                $q->where('student_id_number', $studentNumber);
            })
            ->where('course_id', $courseId)
            ->where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->first();
    }

    public function getUnpublishedMarks(int $courseId, int $perPage, array $filters = [])
    {
        $query = StudentCourse::with([
            'course.universalCourse',
            'student.person',
            'parts.coursePart'
        ])
            ->where('course_id', $courseId)
            ->whereHas('parts', function ($q) {
                $q->where('published', 0);
            });

        if (!empty($filters['course_name'])) {
            $query->whereHas('course.universalCourse', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['course_name'] . '%');
            });
        }

        if (!empty($filters['academic_year'])) {
            $query->where('academic_year', $filters['academic_year']);
        }

        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        $query->with([
            'parts' => function ($q) {
                $q->where('published', 0)
                    ->with('coursePart');
            }
        ]);

        return $query->paginate($perPage);
    }

    public function publishMarks(int $courseId, array $filters = [])
    {
        $query = StudentCoursePart::query()
            ->where('published', 0)
            ->whereHas('studentCourse', function ($q) use ($filters, $courseId) {
                $q->where('course_id', $courseId);
                if (!empty($filters['academic_year'])) {
                    $q->where('academic_year', $filters['academic_year']);
                }
                if (!empty($filters['semester'])) {
                    $q->where('semester', $filters['semester']);
                }
                if (!empty($filters['course_name'])) {
                    $q->whereHas('course.universalCourse', function ($qq) use ($filters) {
                        $qq->where('name', 'like', '%' . $filters['course_name'] . '%');
                    });
                }
            });
        return $query->update([
            'published' => 1
        ]);
    }
    public function getCourseStudents(
        int $collegeId,
        int $courseId,
        array $filters,
        int $perPage = 15
    ): LengthAwarePaginator {
        $user = Auth::user();
        $personId = $user->person_id;
        if (!$personId) {
            abort(403, 'لا يوجد سجل شخص مرتبط بحسابك.');
        }
        $role = $this->determineRole($user);
        $roleId = $this->getRoleId($personId, $role);

        if (!$roleId) {
            abort(403, 'أنت لست معيداً ولا دكتوراً في هذه المادة.');
        }
        $hasAccess = $this->checkCourseStaffAccess($courseId, $role, $roleId);
        if (!$hasAccess) {
            abort(403, 'أنت غير مرتبط بهذه المادة (لا تدرسها كدكتور ولا كمعيد).');
        }
        $query = $this->model->newQuery()
            ->with(['student.person', 'parts.coursePart'])
            ->where('course_id', $courseId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['academic_year'])) {
            $query->where('academic_year', $filters['academic_year']);
        }

        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        if (!empty($filters['student_id_number'])) {
            $query->whereHas('student', function ($q) use ($filters) {
                $q->where('student_id_number', $filters['student_id_number']);
            });
        }

        if (!empty($filters['student_name'])) {
            $query->whereHas('student.person', function ($q) use ($filters) {
                $q->where('full_name', 'LIKE', '%' . $filters['student_name'] . '%');
            });
        }
        $query->orderBy(
            StudentCourse::select('persons.full_name')
                ->join('students', 'students.id', '=', 'student_courses.student_id')
                ->join('persons', 'persons.id', '=', 'students.person_id')
                ->whereColumn('students.id', 'student_courses.student_id')
                ->limit(1),
            'asc'
        );

        return $query->paginate($perPage);
    }
    private function determineRole($user): string
    {
        if ($user->hasRole('TeachingAssistant')) {
            return 'ta';
        }

        if ($user->hasRole('Instructor')) {
            return 'doctor';
        }
        abort(403, 'ليس لديك صلاحية للوصول إلى طلاب هذه المادة.');
    }
    private function getRoleId(int $personId, string $role): ?int
    {
        if ($role === 'ta') {
            return \App\Models\TeachingAssistant::where('person_id', $personId)->value('id');
        }

        if ($role === 'doctor') {
            return \App\Models\Doctor::where('person_id', $personId)->value('id');
        }

        return null;
    }
    private function checkCourseStaffAccess(int $courseId, string $role, int $roleId): bool
    {
        return CourseStaff::where('course_id', $courseId)
            ->when($role === 'ta', function ($q) use ($roleId) {
                $q->where('ta_id', $roleId);
            })
            ->when($role === 'doctor', function ($q) use ($roleId) {
                $q->where('doctor_id', $roleId);
            })
            ->exists();
    }
    public function getCoursePartsWithStudentParts(int $studentCourseId): array
    {
        $studentCourse = $this->model->find($studentCourseId);
        if (!$studentCourse) {
            return [];
        }
        $results = DB::table('course_parts')
            ->leftJoin('student_course_parts', function ($join) use ($studentCourseId) {
                $join->on('course_parts.id', '=', 'student_course_parts.course_part_id')
                    ->where('student_course_parts.student_course_id', '=', $studentCourseId);
            })
            ->where('course_parts.course_id', '=', $studentCourse->course_id)
            ->select([
                'course_parts.id as course_part_id',
                'course_parts.name as course_part_name',
                'course_parts.percentage as course_part_percentage',
                'student_course_parts.id as student_course_part_id',
                'student_course_parts.credits as credits',
                'student_course_parts.published as published',
            ])
            ->orderBy('course_parts.id')
            ->get();

        return $results->map(function ($row) {
            $studentPart = null;
            if ($row->student_course_part_id !== null) {
                $studentPart = [
                    'student_course_part_id' => $row->student_course_part_id,
                    'credits' => (float) $row->credits,
                    'published' => (bool) $row->published,
                ];
            }

            return [
                'course_part_id' => (int) $row->course_part_id,
                'course_part_name' => $row->course_part_name,
                'course_part_percentage' => (float) $row->course_part_percentage,
                'student_course_part' => $studentPart,
            ];
        })->values()->toArray();
    }
}
