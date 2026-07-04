<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class CustomNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $title;
    protected $body;
    protected $color;
    protected $notifiable;

    public function __construct(string $title, string $body, string $color)
    {
        $this->title = $title;
        $this->body = $body;
        $this->color = $color;
    }


    public function via(object $notifiable): array
    {
        $this->notifiable = $notifiable;
        return ['database', 'broadcast'];
    }


    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'user_id' => $notifiable->id,
            'username' => $notifiable->username ?? null,
            'type' => class_basename(static::class),
            'timestamp' => now()->toISOString(),
        ];
    }


    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->title,
            'body' => $this->body,
            'color' => $this->color,
            'timestamp' => now()->toISOString(),
        ]);
    }


    public function broadcastOn(): array
    {
        return [new PrivateChannel('App.Models.User.' . $this->notifiable->id)];
    }


    public function broadcastAs(): string
    {
        return 'custom.notification';
    }
}
