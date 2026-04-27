<?php

namespace App\Repositories;

use App\Models\SanctionType;
use App\Repositories\Contracts\SanctionTypeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SanctionTypeRepository implements SanctionTypeRepositoryInterface
{
    public function __construct(private SanctionType $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): SanctionType
    {
        return $this->model->create($data);
    }

    public function update(SanctionType $sanctionType, array $data): bool
    {
        return $sanctionType->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?SanctionType
    {
        return $this->model->find($id);
    }
}