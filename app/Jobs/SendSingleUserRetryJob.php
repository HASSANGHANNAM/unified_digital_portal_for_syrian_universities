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

class SendSingleUserRetryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $userId;
    public string $title;
    public string $message;
    public string $type;
    public ?int $advertisementId;

    public function __construct(
        int $userId,
        string $title,
        string $message,
        string $type,
        ?int $advertisementId = null
    ) {
        $this->userId = $userId;
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->advertisementId = $advertisementId;
    }

    public function handle(): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            Log::warning('المستخدم غير موجود لإعادة المحاولة.', ['user_id' => $this->userId]);
            return;
        }

        try {
            event(new SendCustomNotification($user, $this->title, $this->message, $this->type));
        } catch (Throwable $e) {
            Log::error('فشل إعادة محاولة البث للمستخدم.', [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function backoff(): array
    {
        return [60, 300, 600];
    }

    public function failed(Throwable $exception): void
    {
        FailedBroadcastJob::create([
            'user_ids' => [$this->userId],
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'attempts' => 3,
            'last_attempt_at' => now(),
            'advertisement_id' => $this->advertisementId,
        ]);

        Log::error('استنفاذ جميع محاولات إعادة البث للمستخدم.', [
            'user_id' => $this->userId,
            'error' => $exception->getMessage(),
        ]);
    }
}
