<?php

namespace App\Repositories;

use App\Models\Sanction;
use App\Repositories\Contracts\SanctionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SanctionRepository implements SanctionRepositoryInterface
{
    public function __construct(private Sanction $model) {}

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
}