<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseStaff;
use App\Models\Doctor;
use App\Models\TeachingAssistant;
use App\Models\User;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CourseRepository implements CourseRepositoryInterface
{
    public function __construct(private Course $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Course
    {
        return $this->model->create($data);
    }

    public function update(Course $course, array $data): bool
    {
        return $course->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Course
    {
        return $this->model->find($id);
    }

    public function getCollegeCourses(int $collegeId)
    {
        return Course::with('universalCourse')
            ->where('college_id', $collegeId)
            ->orderBy('code')
            ->get();
    }

    public function getCourseWithParts(int $courseId, int $perPage = 15)
    {
        return $this->model->with(['courseParts', 'universalCourse'])->find($courseId);
    }

    public function hasCourseAccess(User $user, int $courseId): array
    {
        if ($user->hasRole('Instructor')) {

            $doctor = Doctor::with('department.college')
                ->where('person_id', $user->person_id)
                ->first();

            if (!$doctor) {
                return [
                    'status' => false,
                    'message' => 'Doctor not found.',
                    'code' => 404,
                ];
            }

            $exists = CourseStaff::where('course_id', $courseId)
                ->where('doctor_id', $doctor->id)
                ->whereHas('course.department.college', function ($query) use ($doctor) {
                    $query->where(
                        'university_id',
                        $doctor->department->college->university_id
                    );
                })
                ->exists();

            if (!$exists) {
                return [
                    'status' => false,
                    'message' => 'You are not allowed to manage this course.',
                    'code' => 403,
                ];
            }
        } elseif ($user->hasRole('TeachingAssistant')) {

            $assistant = TeachingAssistant::with('department.college')
                ->where('person_id', $user->person_id)
                ->first();

            if (!$assistant) {
                return [
                    'status' => false,
                    'message' => 'Teaching assistant not found.',
                    'code' => 404,
                ];
            }

            $exists = CourseStaff::where('course_id', $courseId)
                ->where('ta_id', $assistant->id)
                ->whereHas('course.department.college', function ($query) use ($assistant) {
                    $query->where(
                        'university_id',
                        $assistant->department->college->university_id
                    );
                })
                ->exists();

            if (!$exists) {
                return [
                    'status' => false,
                    'message' => 'You are not allowed to manage this course.',
                    'code' => 403,
                ];
            }
        } else {

            return [
                'status' => false,
                'message' => 'Unauthorized.',
                'code' => 403,
            ];
        }

        return [
            'status' => true,
            'message' => '',
            'code' => 200,
        ];
    }
}
