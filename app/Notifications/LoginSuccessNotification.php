<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LoginSuccessNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public function __construct(public string $message) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->payload($notifiable);
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->payload($notifiable));
    }

    public function broadcastAs(): string
    {
        return 'login.success';
    }

    protected function payload(object $notifiable): array
    {
        return [
            'title' => 'تسجيل دخول ناجح',
            'body' => $this->message,
            'user_id' => $notifiable->id,
            'username' => $notifiable->username ?? null,
            'type' => class_basename(static::class),
            'timestamp' => now()->toISOString(),
        ];
    }
}