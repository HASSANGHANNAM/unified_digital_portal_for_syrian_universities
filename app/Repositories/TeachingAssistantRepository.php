<?php

namespace App\Repositories;

use App\Models\TeachingAssistant;
use App\Repositories\Contracts\TeachingAssistantRepositoryInterface;
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
}