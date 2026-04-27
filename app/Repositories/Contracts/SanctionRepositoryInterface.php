<?php

namespace App\Repositories\Contracts;

use App\Models\Sanction;
use Illuminate\Database\Eloquent\Collection;

interface SanctionRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Sanction;
    public function update(Sanction $sanction, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Sanction;
}