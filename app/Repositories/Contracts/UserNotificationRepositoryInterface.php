<?php

namespace App\Repositories\Contracts;

use App\Models\UserNotification;
use Illuminate\Database\Eloquent\Collection;

interface UserNotificationRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): UserNotification;
    public function update(UserNotification $userNotification, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?UserNotification;
}