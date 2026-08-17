<?php

namespace App\Repositories;

use App\Models\University;
use App\Repositories\Contracts\UniversityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UniversityRepository implements UniversityRepositoryInterface
{
    public function __construct(private University $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): University
    {
        return $this->model->create($data);
    }

    public function update(University $university, array $data): bool
    {
        return $university->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?University
    {
        return $this->model->find($id);
    }
    public function getUniversitiesWithColleges(array $filters): Collection
    {
        $query = $this->model->newQuery()
            ->with([
                'colleges.dean.person',
            ])
            ->orderBy('name', 'asc');

        if (!empty($filters['university_id'])) {
            $query->where('id', $filters['university_id']);
        }

        return $query->get();
    }
}
