<?php

namespace App\Repositories\Contracts;

use App\Models\RequestType;
use Illuminate\Database\Eloquent\Collection;

interface RequestTypeRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): RequestType;
    public function update(RequestType $requestType, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?RequestType;
}