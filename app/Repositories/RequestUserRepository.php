<?php

namespace App\Repositories;

use App\Models\RequestUser;
use App\Repositories\Contracts\RequestUserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RequestUserRepository implements RequestUserRepositoryInterface
{
    public function __construct(private RequestUser $model) {}

    public function create(array $data): RequestUser
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->findById($id);
        if (!$record) return false;
        return $record->update($data);
    }

    public function findById(int $id): ?RequestUser
    {
        return $this->model->find($id);
    }

    public function getByRequestId(int $requestId): Collection
    {
        return $this->model->where('request_id', $requestId)
            ->orderBy('signed_at')
            ->get();
    }

    public function getByUserAndRequest(int $userId, int $requestId): ?RequestUser
    {
        return $this->model->where('user_id', $userId)
            ->where('request_id', $requestId)
            ->first();
    }

    public function updateStatus(int $id, string $status, ?string $notes = null): bool
    {
        $record = $this->findById($id);
        if (!$record) return false;

        $data = ['status' => $status];
        if ($notes !== null) {
            $data['notes'] = $notes;
        }

        if (in_array($status, ['approved', 'rejected'])) {
            $data['signed_at'] = now();
        }

        return $record->update($data);
    }
}
