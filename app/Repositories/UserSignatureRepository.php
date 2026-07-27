<?php

namespace App\Repositories;

use App\Models\UserSignature;
use App\Repositories\Contracts\UserSignatureRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserSignatureRepository implements UserSignatureRepositoryInterface
{
    public function __construct(private UserSignature $model) {}
    public function create(int $userId, string $uuid, string $path): UserSignature
    {
        return $this->model->create([
            'user_id' => $userId,
            'signature_uuid' => $uuid,
            'path' => $path,
        ]);
    }
    public function getLatestByUserId(int $userId): ?UserSignature
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
    }
    public function findByUuid(string $uuid): ?UserSignature
    {
        return $this->model->where('signature_uuid', $uuid)->first();
    }
    public function getAllByUserId(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function delete(int $id): bool
    {
        $signature = $this->findById($id);
        if (!$signature) {
            return false;
        }
        return $signature->delete();
    }
    public function findById(int $id): ?UserSignature
    {
        return $this->model->find($id);
    }
}
