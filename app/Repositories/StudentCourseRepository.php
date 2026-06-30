<?php

namespace App\Repositories;

use App\Models\StudentCourse;
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
            ->where('course_id', $courseId)
            ->first();
    }

    public function getStudentCoursesWithGrades(int $studentId, int $perPage = 10)
    {
        return $this->model->with([
            'course.universalCourse',
            'course.department',
            'parts.coursePart',
        ])
        ->where('student_id', $studentId)
        ->paginate($perPage);
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

    public function getCourseGrades(int $courseId,string $academicYear,int $semester,int $perPage = 10)
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

    public function findStudentCourseByStudentNumber(string $studentNumber,int $courseId,string $academicYear,int $semester)
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




}
