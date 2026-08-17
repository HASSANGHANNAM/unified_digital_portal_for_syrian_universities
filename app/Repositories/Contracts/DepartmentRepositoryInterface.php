<?php

namespace App\Repositories\Contracts;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

interface DepartmentRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Department;
    public function update(Department $department, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Department;
    public function getDepartments(array $filters): Collection;
}
