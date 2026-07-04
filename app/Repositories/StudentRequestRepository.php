<?php

namespace App\Repositories;

use App\Models\StudentRequest;
use App\Repositories\Contracts\StudentRequestRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StudentRequestRepository implements StudentRequestRepositoryInterface
{
    public function __construct(private StudentRequest $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): StudentRequest
    {
        return $this->model->create($data);
    }

    public function update(StudentRequest $studentRequest, array $data): bool
    {
        return $studentRequest->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?StudentRequest
    {
        return $this->model->find($id);
    }


}
