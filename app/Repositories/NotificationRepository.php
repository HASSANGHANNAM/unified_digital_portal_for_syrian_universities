<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use Ramsey\Collection\Collection;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function __construct(private Notification $notification) {}

    public function all(): Collection
    {
        return new Collection(Notification::class, Notification::all()->toArray());
    }

    public function create(array $data): Notification
    {
        return $this->notification->create([
            'user_id' => $data['user_id'],
            'complaint_id' => $data['complaint_id'] ?? null,
            'type' => $data['type'] ?? null,
            'payload' => $data['payload'] ?? null,
            'is_read' => $data['is_read'] ?? false,
            'sent_at' => $data['sent_at'] ?? null,
        ]);
    }

    public function update(Notification $notification, array $data): bool
    {
        return $notification->update($data);
    }

    public function delete(int $id): bool
    {
        $notification = $this->findById($id);
        if (!$notification) {
            return false;
        }
        return $notification->delete();
    }

    public function findById(int $id): ?Notification
    {
        return $this->notification->find($id);
    }
}

