<?php

namespace App\Repositories;

use App\Models\StudentVerification;
use App\Repositories\Contracts\StudentVerificationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StudentVerificationRepository implements StudentVerificationRepositoryInterface
{
    public function __construct(
        private StudentVerification $model
    ) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): StudentVerification
    {
        return $this->model->create($data);
    }

    public function findById(string $id): ?StudentVerification
    {
        return $this->model->find($id);
    }

    public function query()
    {
        return $this->model->query();
    }
}
