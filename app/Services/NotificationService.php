<?php

namespace App\Services;

use App\Jobs\ProcessQueryableUserNotificationsJob;
use App\Models\User;
use App\Services\Traits\HasCache;
use App\Services\Traits\HasLogging;
use App\Support\NotificationChunkWriter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Laravel\SerializableClosure\SerializableClosure;

class NotificationService
{
    use HasCache;
    use HasLogging;

    public function sendToUser(User $user, Notification $notification): void
    {
        $this->logInfo('notification.send_to_user', ['user_id' => $user->id, 'type' => $notification::class]);

        if ($this->shouldQueueNotification($notification)) {
            $user->notify($notification);

            return;
        }

        $user->notifyNow($notification);
    }

    /**
     * @param  iterable<int>|array<int>  $userIds
     */
    public function sendToUsers(iterable $userIds, Notification $notification): void
    {
        $ids = $this->normalizeIds($userIds);
        $count = count($ids);

        if ($count === 0) {
            return;
        }

        $this->logInfo('notification.send_to_users', ['count' => $count, 'type' => $notification::class]);

        if ($count <= $this->syncThreshold()) {
            $this->sendToUserIdsSynchronously($ids, $notification);

            return;
        }

        $this->dispatchBulkJob(
            $notification,
            new SerializableClosure(fn () => User::query()->whereIn('id', $ids)->orderBy('id'))
        );
    }

    public function sendToRole(string $roleName, Notification $notification): void
    {
        $query = User::query()->whereHas('roles', fn (Builder $q) => $q->where('name', $roleName));
        $count = (clone $query)->count();

        $this->logInfo('notification.send_to_role', ['role' => $roleName, 'count' => $count, 'type' => $notification::class]);

        if ($count === 0) {
            return;
        }

        if ($count <= $this->syncThreshold()) {
            $chunk = (int) config('notifications.chunk_size', 500);
            (clone $query)->chunkById($chunk, function (Collection $users) use ($notification): void {
                NotificationChunkWriter::writeAndBroadcast($users, $notification);
            });

            return;
        }

        $this->dispatchBulkJob(
            $notification,
            new SerializableClosure(fn () => User::query()->whereHas('roles', fn (Builder $q) => $q->where('name', $roleName))->orderBy('id'))
        );
    }

    /**
     * The closure must return an Eloquent Builder for {@see User} (ordered by id for chunkById).
     */
    public function sendToCustomQuery(callable $userQueryFactory, Notification $notification): void
    {
        /** @var Builder $query */
        $query = $userQueryFactory(User::query());

        if (! $query instanceof Builder) {
            $this->logWarning('notification.send_to_custom_query.invalid_builder');

            return;
        }

        $count = (clone $query)->count();

        $this->logInfo('notification.send_to_custom_query', ['count' => $count, 'type' => $notification::class]);

        if ($count === 0) {
            return;
        }

        if ($count <= $this->syncThreshold()) {
            $chunk = (int) config('notifications.chunk_size', 500);
            (clone $query)->chunkById($chunk, function (Collection $users) use ($notification): void {
                NotificationChunkWriter::writeAndBroadcast($users, $notification);
            });

            return;
        }

        $this->dispatchBulkJob(
            $notification,
            new SerializableClosure(function () use ($userQueryFactory) {
                $built = $userQueryFactory(User::query());

                return $built instanceof Builder ? $built->orderBy('id') : User::query()->whereRaw('1 = 0');
            })
        );
    }

    protected function syncThreshold(): int
    {
        return max(1, (int) config('notifications.sync_recipient_threshold', 1));
    }

    protected function shouldQueueNotification(Notification $notification): bool
    {
        return $notification instanceof \Illuminate\Contracts\Queue\ShouldQueue;
    }

    /**
     * @param  array<int>  $ids
     */
    protected function sendToUserIdsSynchronously(array $ids, Notification $notification): void
    {
        $chunkSize = (int) config('notifications.chunk_size', 500);

        foreach (array_chunk($ids, $chunkSize) as $chunk) {
            $users = User::query()->whereIn('id', $chunk)->get();

            foreach ($users as $user) {
                if ($this->shouldQueueNotification($notification)) {
                    $user->notify($notification);
                } else {
                    $user->notifyNow($notification);
                }
            }
        }
    }

    protected function dispatchBulkJob(Notification $notification, SerializableClosure $factory): void
    {
        Bus::dispatch(new ProcessQueryableUserNotificationsJob($notification, $factory));
    }

    /**
     * @param  iterable<int>|array<int>  $userIds
     * @return array<int>
     */
    protected function normalizeIds(iterable $userIds): array
    {
        return array_values(array_unique(array_map('intval', is_array($userIds) ? $userIds : iterator_to_array($userIds))));
    }
}
