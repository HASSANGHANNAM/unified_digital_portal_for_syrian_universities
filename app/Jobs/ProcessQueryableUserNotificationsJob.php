<?php

namespace App\Jobs;

use App\Support\NotificationChunkWriter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Laravel\SerializableClosure\SerializableClosure;

class ProcessQueryableUserNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Notification $notification,
        public SerializableClosure $userQueryFactory
    ) {}

    public function handle(): void
    {
        $query = ($this->userQueryFactory)();

        if (! $query instanceof Builder) {
            Log::warning('ProcessQueryableUserNotificationsJob: query factory did not return an Eloquent Builder.');

            return;
        }

        $chunk = (int) config('notifications.chunk_size', 500);

        (clone $query)->chunkById($chunk, function ($users): void {
            NotificationChunkWriter::writeAndBroadcast($users, $this->notification);
        });
    }
}
