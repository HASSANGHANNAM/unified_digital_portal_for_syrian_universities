<?php

namespace App\Repositories\Contracts;

use App\Models\CourseStaff;
use Illuminate\Database\Eloquent\Collection;

interface CourseStaffRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): CourseStaff;
    public function update(CourseStaff $courseStaff, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?CourseStaff;
}