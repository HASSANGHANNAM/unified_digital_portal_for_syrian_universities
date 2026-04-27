<?php

namespace App\Repositories\Contracts;

use App\Models\CollegeDean;
use Illuminate\Database\Eloquent\Collection;

interface CollegeDeanRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): CollegeDean;
    public function update(CollegeDean $collegeDean, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?CollegeDean;
}