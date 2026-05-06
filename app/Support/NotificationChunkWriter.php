<?php

namespace App\Support;

use App\Events\DatabaseNotificationBroadcast;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationChunkWriter
{
    /**
     * Batch-insert database notification rows and broadcast to each user's private channel.
     *
     * @param  Collection<int, User>  $users
     */
    public static function writeAndBroadcast(Collection $users, Notification $notification): void
    {
        if ($users->isEmpty()) {
            return;
        }

        $now = now();
        $rows = [];
        $pairs = [];

        foreach ($users as $user) {
            $id = (string) Str::uuid();
            $data = $notification->toDatabase($user);
            $rows[] = [
                'id' => $id,
                'type' => $notification::class,
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode($data, JSON_THROW_ON_ERROR),
                'read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $pairs[] = ['user_id' => $user->id, 'id' => $id, 'data' => $data];
        }

        DB::table('notifications')->insert($rows);

        foreach ($pairs as $pair) {
            event(new DatabaseNotificationBroadcast(
                $pair['user_id'],
                array_merge($pair['data'], [
                    'id' => $pair['id'],
                    'notification_class' => $notification::class,
                ])
            ));
        }
    }
}
