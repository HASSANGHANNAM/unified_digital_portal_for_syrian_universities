<?php

namespace App\Repositories\Contracts;

use App\Models\College;
use Illuminate\Database\Eloquent\Collection;

interface CollegeRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): College;
    public function update(College $college, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?College;
}