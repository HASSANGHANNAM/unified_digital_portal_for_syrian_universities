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

    public string $message;

    /**
     * Create a new notification instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  object  $notifiable
     * @return array
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  object  $notifiable
     * @return array
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->payload($notifiable);
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  object  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->payload($notifiable));
    }

    /**
     * Get the event name for the broadcast.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'custom.notification';
    }

    /**
     * Get the notification payload.
     *
     * @param  object  $notifiable
     * @return array
     */
    protected function payload(object $notifiable): array
    {
        return [
            'title' => 'إشعار مخصص',
            'body' => $this->message,
            'user_id' => $notifiable->id,
            'username' => $notifiable->username ?? null,
            'type' => class_basename(static::class),
            'timestamp' => now()->toISOString(),
        ];
    }
}