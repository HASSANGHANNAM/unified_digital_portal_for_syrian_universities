<?php

namespace App\Repositories;

use App\Models\UserNotification;
use App\Repositories\Contracts\UserNotificationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserNotificationRepository implements UserNotificationRepositoryInterface
{
    public function __construct(private UserNotification $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): UserNotification
    {
        return $this->model->create($data);
    }

    public function update(UserNotification $userNotification, array $data): bool
    {
        return $userNotification->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?UserNotification
    {
        return $this->model->find($id);
    }
}