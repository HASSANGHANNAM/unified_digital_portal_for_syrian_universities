<?php

namespace App\Repositories\Contracts;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Collection;

interface StaffRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Staff;
    public function update(Staff $staff, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Staff;
}