<?php

namespace App\Repositories\Contracts;

use App\Models\RequestMedia;
use Illuminate\Database\Eloquent\Collection;

interface RequestMediaRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): RequestMedia;
    public function update(RequestMedia $requestMedia, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?RequestMedia;
}