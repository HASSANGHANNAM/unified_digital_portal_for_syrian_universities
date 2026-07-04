<?php

namespace App\Repositories\Contracts;

use App\Models\RequestUser;
use Illuminate\Database\Eloquent\Collection;

interface RequestUserRepositoryInterface
{
    public function create(array $data): RequestUser;
    public function update(int $id, array $data): bool;
    public function findById(int $id): ?RequestUser;
    public function getByRequestId(int $requestId): Collection;
    public function getByUserAndRequest(int $userId, int $requestId): ?RequestUser;
    public function updateStatus(int $id, string $status, ?string $notes = null): bool;
}
