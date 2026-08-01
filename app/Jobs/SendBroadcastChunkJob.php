<?php

namespace App\Jobs;

use App\Events\SendCustomNotification;
use App\Models\FailedBroadcastJob;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;
use RuntimeException;

class SendBroadcastChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $userIds;
    public string $title;
    public string $message;
    public string $type;
    public ?int $failedBroadcastJobId;

    public function __construct(array $userIds, string $title, string $message, string $type, ?int $failedBroadcastJobId = null)
    {
        $this->userIds = array_values(array_unique(array_map('intval', $userIds)));
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->failedBroadcastJobId = $failedBroadcastJobId;
    }

    public function handle(): void
    {
        $users = User::query()->whereIn('id', $this->userIds)->get();
        $successfulCount = 0;
        $failedCount = 0;

        foreach ($users as $user) {
            try {
                event(new SendCustomNotification($user, $this->title, $this->message, $this->type));
                $successfulCount++;
            } catch (Throwable $exception) {
                $failedCount++;

                Log::error('Broadcast chunk user delivery failed.', [
                    'user_id' => $user->id,
                    'user_ids' => $this->userIds,
                    'title' => $this->title,
                    'message' => $this->message,
                    'type' => $this->type,
                    'exception' => get_class($exception),
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        if ($failedCount > 0 && $failedCount >= $successfulCount) {
            throw new RuntimeException('Broadcast chunk failed for the majority of recipients.');
        }

        if ($this->failedBroadcastJobId !== null) {
            FailedBroadcastJob::query()->whereKey($this->failedBroadcastJobId)->delete();
        }
    }

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function failed(Throwable $exception): void
    {
        $record = null;

        if ($this->failedBroadcastJobId !== null) {
            $record = FailedBroadcastJob::query()->find($this->failedBroadcastJobId);
        }

        if ($record) {
            $record->update([
                'user_ids' => $this->userIds,
                'title' => $this->title,
                'message' => $this->message,
                'type' => $this->type,
                'attempts' => $record->attempts + 1,
                'last_attempt_at' => now(),
            ]);
        } else {
            FailedBroadcastJob::query()->create([
                'user_ids' => $this->userIds,
                'title' => $this->title,
                'message' => $this->message,
                'type' => $this->type,
                'attempts' => 1,
                'last_attempt_at' => now(),
            ]);
        }

        Log::error('Broadcast chunk failed after all attempts.', [
            'user_ids' => $this->userIds,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'failed_broadcast_job_id' => $this->failedBroadcastJobId,
            'exception' => get_class($exception),
            'error' => $exception->getMessage(),
        ]);
    }
}
