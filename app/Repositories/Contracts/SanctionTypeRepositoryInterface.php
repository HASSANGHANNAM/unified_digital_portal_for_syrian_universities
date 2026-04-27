<?php

namespace App\Repositories\Contracts;

use App\Models\SanctionType;
use Illuminate\Database\Eloquent\Collection;

interface SanctionTypeRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): SanctionType;
    public function update(SanctionType $sanctionType, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?SanctionType;
}