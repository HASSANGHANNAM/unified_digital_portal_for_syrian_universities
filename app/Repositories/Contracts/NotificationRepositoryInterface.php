<?php

namespace App\Repositories\Contracts;

use App\Models\Notification;
use Ramsey\Collection\Collection;

interface NotificationRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Notification;
    public function update(Notification $notification, array $data): bool;
    public function delete(int $id): bool;
    public function findById(int $id): ?Notification;
}

