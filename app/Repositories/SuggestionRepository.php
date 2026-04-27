<?php

namespace App\Repositories;

use App\Models\Suggestion;
use App\Repositories\Contracts\SuggestionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SuggestionRepository implements SuggestionRepositoryInterface
{
    public function __construct(private Suggestion $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Suggestion
    {
        return $this->model->create($data);
    }

    public function update(Suggestion $suggestion, array $data): bool
    {
        return $suggestion->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Suggestion
    {
        return $this->model->find($id);
    }
}