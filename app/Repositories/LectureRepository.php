<?php

namespace App\Repositories;

use App\Models\Lecture;
use App\Repositories\Contracts\LectureRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LectureRepository implements LectureRepositoryInterface
{
    public function __construct(private Lecture $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Lecture
    {
        return $this->model->create($data);
    }

    public function update(Lecture $lecture, array $data): bool
    {
        return $lecture->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Lecture
    {
        return $this->model->find($id);
    }
    public function findByCoursePartId(int $coursePartsId): Collection
    {
        return $this->model->where('course_parts_id', $coursePartsId)
            ->orderBy('order_index', 'asc')
            ->get();
    }
}
