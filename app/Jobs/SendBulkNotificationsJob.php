<?php

namespace App\Jobs;

use App\Models\User;
use App\Events\SendCustomNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendBulkNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $userIds;
    public string $title;
    public string $message;
    public string $color;

    public function __construct(array $userIds, string $title, string $message, string $color = 'info')
    {
        $this->userIds = $userIds;
        $this->title = $title;
        $this->message = $message;
        $this->color = $color;
    }

    public function handle(): void
    {
        $users = User::whereIn('id', $this->userIds)->get();

        foreach ($users as $user) {
            try {
                event(new SendCustomNotification($user, $this->title, $this->message, $this->color));
            } catch (Throwable $e) {
                Log::error('SendBulkNotificationsJob broadcast failed for user.', [
                    'user_id' => $user->id,
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'title' => $this->title,
                    'message_body' => $this->message,
                    'color' => $this->color,
                ]);
            }
        }
    }
}
