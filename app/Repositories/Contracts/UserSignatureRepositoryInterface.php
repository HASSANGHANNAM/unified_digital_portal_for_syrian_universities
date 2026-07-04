<?php

namespace App\Repositories\Contracts;

use App\Models\UserSignature;
use Illuminate\Database\Eloquent\Collection;

interface UserSignatureRepositoryInterface
{
    public function create(int $userId, string $uuid): UserSignature;
    public function getLatestByUserId(int $userId): ?UserSignature;
    public function findByUuid(string $uuid): ?UserSignature;
    public function getAllByUserId(int $userId): Collection;
    public function delete(int $id): bool;
    public function findById(int $id): ?UserSignature;
}
