<?php

namespace App\Repositories\Contracts;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Collection;

interface LectureRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Lecture;
    public function update(Lecture $lecture, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Lecture;
    public function findByCoursePartId(int $coursePartsId): Collection;
}
