<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\TeachingAssistant;
use App\Repositories\Contracts\TeachingAssistantRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TeachingAssistantRepository implements TeachingAssistantRepositoryInterface
{
    public function __construct(private TeachingAssistant $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): TeachingAssistant
    {
        return $this->model->create($data);
    }

    public function update(TeachingAssistant $teachingAssistant, array $data): bool
    {
        return $teachingAssistant->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?TeachingAssistant
    {
        return $this->model->find($id);
    }

    public function getUniversitiesByPersonId(int $personId): array
    {
        $teachingAssistants = $this->model->newQuery()
            ->where('person_id', $personId)
            ->with(['department.college.university'])
            ->get();

        $universities = [];

        foreach ($teachingAssistants as $teachingAssistant) {
            $department = $teachingAssistant->department;
            $college = $department?->college;
            $university = $college?->university;

            if (!$department || !$college || !$university) {
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
            if (!isset($universities[$universityId]['colleges'][$collegeId])) {
                $universities[$universityId]['colleges'][$collegeId] = [
                    'college_id' => $collegeId,
                    'college_name' => $college->name,
                    'departments' => [],
                ];
            }

            $departmentId = (int) $department->id;
            if (!isset($universities[$universityId]['colleges'][$collegeId]['departments'][$departmentId])) {
                $universities[$universityId]['colleges'][$collegeId]['departments'][$departmentId] = [
                    'department_id' => $departmentId,
                    'department_name' => $department->name,
                ];
            }
        }

        return array_values(array_map(function (array $university) {
            $university['colleges'] = array_values(array_map(function (array $college) {
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
            ->whereHas('courseStaff.teachingAssistant', function ($builder) use ($personId) {
                $builder->where('person_id', $personId);
            })
            ->where('college_id', $collegeId)
            ->with(['universalCourse', 'department', 'college.university']);

        if (!empty($filters['department_id'])) {
            $query->where('department_id', (int) $filters['department_id']);
        }

        if (!empty($filters['course_name'])) {
            $query->where(function ($builder) use ($filters) {
                $builder->whereHas('universalCourse', function ($subQuery) use ($filters) {
                    $subQuery->where('name', 'like', '%' . $filters['course_name'] . '%');
                })->orWhere('code', 'like', '%' . $filters['course_name'] . '%');
            });
        }

        return $page
            ? $query->paginate($perPage, ['*'], 'page', $page)
            : $query->paginate($perPage);
    }
    public function getTeachingAssistants(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['person', 'department.college'])
            ->orderBy('id');

        if (!empty($filters['college_id'])) {
            $query->whereHas('department', function ($q) use ($filters) {
                $q->where('college_id', $filters['college_id']);
            });
        }

        if (!empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('ta_id_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('person', function ($p) use ($search) {
                        $p->where('full_name', 'LIKE', "%{$search}%");
                    });
            });
        }

        return $query->paginate($perPage);
    }
}
