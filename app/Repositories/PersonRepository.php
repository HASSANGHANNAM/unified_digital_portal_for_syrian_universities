<?php

namespace App\Repositories;

use App\Models\Person;
use App\Repositories\Contracts\PersonRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PersonRepository implements PersonRepositoryInterface
{
    public function __construct(private Person $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Person
    {
        return $this->model->create($data);
    }

    public function update(Person $person, array $data): Person
    {
        $person->update(array_filter($data, fn ($value) => !is_null($value)));
        return $person->refresh();
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Person
    {
        return $this->model->find($id);
    }
}
