<?php

namespace App\Repositories;

use App\Models\Staff;
use App\Repositories\Contracts\StaffRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StaffRepository implements StaffRepositoryInterface
{
    public function __construct(private Staff $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Staff
    {
        return $this->model->create($data);
    }

    public function update(Staff $staff, array $data): bool
    {
        return $staff->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Staff
    {
        return $this->model->find($id);
    }
    public function getStaff(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['person', 'department.college'])
            ->orderBy('id');
        if ($filters['college_id'] == null) {
            $filters['department_id'] = null;
        } elseif (!empty($filters['college_id'])) {
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
                $q->where('staff_id_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('person', function ($p) use ($search) {
                        $p->where('full_name', 'LIKE', "%{$search}%");
                    });
            });
        }
        return $query->paginate($perPage);
    }
}
