<?php

namespace App\Repositories;

use App\Models\StudentCourse;
use App\Models\StudentCoursePart;
use App\Repositories\Contracts\StudentCourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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
}
