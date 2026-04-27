<?php

namespace App\Repositories;

use App\Models\College;
use App\Repositories\Contracts\CollegeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CollegeRepository implements CollegeRepositoryInterface
{
    public function __construct(private College $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): College
    {
        return $this->model->create($data);
    }

    public function update(College $college, array $data): bool
    {
        return $college->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?College
    {
        return $this->model->find($id);
    }
}