<?php

namespace App\Repositories\Contracts;

use App\Models\DepartmentHead;
use Illuminate\Database\Eloquent\Collection;

interface DepartmentHeadRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): DepartmentHead;
    public function update(DepartmentHead $departmentHead, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?DepartmentHead;
}