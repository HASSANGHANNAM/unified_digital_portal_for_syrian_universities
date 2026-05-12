<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;

class NotificationPolicy
{
    public function delete(User $user, Notification $notification): bool
    {
        return $user->patient?->id === $notification->patient_id;
    }

    public function markAsRead(User $user, Notification $notification): bool
    {
        return $user->patient?->id === $notification->patient_id;
    }

}
