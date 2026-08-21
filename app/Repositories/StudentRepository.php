<?php

namespace App\Repositories;

use App\Models\Student;
use App\Models\User;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Collection;

class StudentRepository implements StudentRepositoryInterface
{
    public function __construct(private Student $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Student
    {
        return $this->model->create($data);
    }

    public function update(Student $student, array $data): bool
    {
        return $student->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Student
    {
        return $this->model->find($id);
    }

    public function findByStudentNumber(string $studentNumber)
    {
        return $this->model
            ->where('student_id_number', $studentNumber)
            ->first();
    }

    public function getHomePage(int $userId)
    {
        $user = User::findOrFail($userId);

        return Student::with([
            'person:id,full_name',
            'department:id,name',
            'college:id,name,university_id',
            'college.university:id,name',
            'requests' => function ($query) {
                $query->latest('submission_date')
                    ->take(4)
                    ->with([
                        'course:id,universal_course_id,code',
                        'course.universalCourse:id,name',
                        'processedBy.person:id,full_name'
                    ]);
            },
        ])->where('person_id', $user->person_id)
            ->first();
    }

    public function getAcademicProfile(int $personId)
    {
        return Student::with([
            'person:id,full_name',
            'department:id,name',
            'college:id,name,university_id',
            'college.university:id,name',

            'sanctions' => function ($q) {
                $q->with([
                    'sanctionType:id,name,reason',
                    'course:id,universal_course_id',
                    'course.universalCourse:id,name'
                ]);
            },

            'courses' => function ($q) {
                $q->with([
                    'course:id,universal_course_id',
                    'course.universalCourse:id,name',
                    'parts:id,student_course_id,credits,created_at'
                ]);
            }
        ])
            ->where('person_id', $personId)
            ->first();
    }
    public function getPendingStudents()
    {
        return Student::with(['person.user'])
            ->whereHas('person.user', function ($query) {
                $query->where('status', 'pending');
            })
            ->get();
    }

    public function getStudentsByCollege(int $collegeId, int $perPage = 15, int $page = 1)
    {
        return Student::where('college_id', $collegeId)
            ->with('person')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function importStudents($file, int $universityId, int $collegeId): array
    {
        $import = new StudentsImport(
            $universityId,
            $collegeId
        );
        Excel::import($import, $file);
        return [
            'report' => $import->getReport(),
            'errors' => $import->getErrors(),
        ];
    }
}
