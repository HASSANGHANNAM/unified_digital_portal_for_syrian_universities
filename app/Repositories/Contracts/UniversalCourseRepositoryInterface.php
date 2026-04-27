<?php

namespace App\Repositories\Contracts;

use App\Models\UniversalCourse;
use Illuminate\Database\Eloquent\Collection;

interface UniversalCourseRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): UniversalCourse;
    public function update(UniversalCourse $universalCourse, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?UniversalCourse;
}