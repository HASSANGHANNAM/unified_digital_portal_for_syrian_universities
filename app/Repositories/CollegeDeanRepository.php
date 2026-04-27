<?php

namespace App\Repositories;

use App\Models\CollegeDean;
use App\Repositories\Contracts\CollegeDeanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CollegeDeanRepository implements CollegeDeanRepositoryInterface
{
    public function __construct(private CollegeDean $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): CollegeDean
    {
        return $this->model->create($data);
    }

    public function update(CollegeDean $collegeDean, array $data): bool
    {
        return $collegeDean->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?CollegeDean
    {
        return $this->model->find($id);
    }
}