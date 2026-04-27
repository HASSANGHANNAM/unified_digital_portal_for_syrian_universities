<?php

namespace App\Repositories\Contracts;

use App\Models\RequestTypeMedia;
use Illuminate\Database\Eloquent\Collection;

interface RequestTypeMediaRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): RequestTypeMedia;
    public function update(RequestTypeMedia $requestTypeMedia, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?RequestTypeMedia;
}