<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Doctor;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DoctorRepository implements DoctorRepositoryInterface
{
    public function __construct(private Doctor $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Doctor
    {
        return $this->model->create($data);
    }

    public function update(Doctor $doctor, array $data): bool
    {
        return $doctor->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Doctor
    {
        return $this->model->find($id);
    }

    public function getUniversitiesByPersonId(int $personId): array
    {
        $doctors = $this->model->newQuery()
            ->where('person_id', $personId)
            ->with(['department.college.university'])
            ->get();

        $universities = [];

        foreach ($doctors as $doctor) {
            $department = $doctor->department;
            $college = $department?->college;
            $university = $college?->university;

            if (!$university) {
                continue;
            }

            $universityId = (int) $university->id;
            if (!isset($universities[$universityId])) {
                $universities[$universityId] = [
                    'university_id' => $universityId,
                    'university_name' => $university->name,
                    'logo_url' => 'V1/universities/' . $universityId . '/logo',
                    'colleges' => [],
                ];
            }

            $collegeId = (int) $college->id;
            $collegeKey = $collegeId;
            if (!isset($universities[$universityId]['colleges'][$collegeKey])) {
                $universities[$universityId]['colleges'][$collegeKey] = [
                    'college_id' => $collegeId,
                    'college_name' => $college->name,
                    'departments' => [],
                ];
            }

            $departmentId = (int) $department->id;
            $departmentKey = $departmentId;
            if (!isset($universities[$universityId]['colleges'][$collegeKey]['departments'][$departmentKey])) {
                $universities[$universityId]['colleges'][$collegeKey]['departments'][$departmentKey] = [
                    'department_id' => $departmentId,
                    'department_name' => $department->name,
                ];
            }
        }

        return array_values(array_map(function ($university) {
            $university['colleges'] = array_values(array_map(function ($college) {
                $college['departments'] = array_values($college['departments']);
                return $college;
            }, $university['colleges']));
            return $university;
        }, $universities));
    }
    public function getCoursesByPersonIdAndCollege(
        int $personId,
        int $collegeId,
        array $filters = [],
        int $perPage = 15,
        ?int $page = null
    ): LengthAwarePaginator {
        $query = Course::query()
            ->whereHas('courseStaff.doctor', function ($q) use ($personId) {
                $q->where('person_id', $personId);
            })
            ->where('college_id', $collegeId) // ✅ الفلترة على الكلية
            ->with([
                'universalCourse',
                'department',
                'college.university'
            ]);

        // فلاتر اختيارية
        if (!empty($filters['department_id'])) {
            $query->where('department_id', (int) $filters['department_id']);
        }

        if (!empty($filters['course_name'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereHas('universalCourse', function ($sub) use ($filters) {
                    $sub->where('name', 'like', '%' . $filters['course_name'] . '%');
                })->orWhere('code', 'like', '%' . $filters['course_name'] . '%');
            });
        }

        // Pagination مع دعم page
        return $page
            ? $query->paginate($perPage, ['*'], 'page', $page)
            : $query->paginate($perPage);
    }
}
