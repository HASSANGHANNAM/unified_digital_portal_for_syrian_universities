<?php

namespace App\Repositories;

use App\Models\Sanction;
use App\Models\SanctionType;
use App\Repositories\Contracts\SanctionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SanctionRepository implements SanctionRepositoryInterface
{
    public function __construct(private Sanction $model, private SanctionType $sanctionTypeModel) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Sanction
    {
        return $this->model->create($data);
    }

    public function update(Sanction $sanction, array $data): bool
    {
        return $sanction->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Sanction
    {
        return $this->model->find($id);
    }

    public function getPaginatedWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->sanctionTypeModel->newQuery();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['reason'])) {
            $query->where('reason', 'like', '%' . $filters['reason'] . '%');
        }

        $query->orderBy('id', 'desc');

        return $query->paginate($perPage);
    }

    public function createSanctionType(array $data): SanctionType
    {
        $data['years'] = isset($data['years']) ? (int) $data['years'] : 0;
        $data['months'] = isset($data['months']) ? (int) $data['months'] : 0;
        $data['days'] = isset($data['days']) ? (int) $data['days'] : 0;
        return $this->sanctionTypeModel->create($data);
    }
    public function getStudentSanctions(int $studentId, int $perPage = 10)
    {
        return $this->model->with(['sanctionType', 'course'])
            ->where('student_id', $studentId)
            ->latest()
            ->paginate($perPage);
    }

    public function getStudentSanctionById(int $studentId, int $sanctionId)
    {
        return $this->model->with(['sanctionType', 'course'])
            ->where('student_id', $studentId)
            ->where('id', $sanctionId)
            ->first();
    }

    public function updateResponse(int $sanctionId, array $data): bool
    {
        $sanction = $this->findById($sanctionId);
        if (!$sanction) {
            return false;
        }
        return $sanction->update($data);
    }

    public function getSanctionsByStudent(int $studentId, int $perPage = 15, int $page = 1)
    {
        return $this->model->where('student_id', $studentId)
            ->with('sanctionType')
            ->paginate($perPage, ['*'], 'page', $page);
    }
    public function getSanctionsByStudentWithFilters(int $studentId, array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with([
                'sanctionType',
                'staff.person',
                'course.universalCourse',
            ])
            ->where('student_id', $studentId)
            ->orderBy('issued_date', 'desc');
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['sanction_type_name'])) {
            $query->whereHas('sanctionType', function ($q) use ($filters) {
                $q->where('name', 'LIKE', '%' . $filters['sanction_type_name'] . '%');
            });
        }

        return $query->paginate($perPage);
    }
}
