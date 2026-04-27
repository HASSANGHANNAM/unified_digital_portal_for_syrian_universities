<?php

namespace App\Repositories;

use App\Models\Doctor;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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
}