<?php

namespace App\Events;

use App\Enums\NotificationColor;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendCustomNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $title;
    public $body;
    public string $colorHex;

    public function __construct($user, string $title, string $body, string $colorName = 'info')
    {
        $this->user = $user;
        $this->title = $title;
        $this->body = $body;

        $enum = NotificationColor::tryFrom(strtoupper($colorName)) ?? NotificationColor::INFO;
        $this->colorHex = $enum->value;
    }

    public function broadcastOn()
    {
        return ['private-App.Models.User.' . $this->user->id];
    }

    public function broadcastAs()
    {
        return 'custom.notification';
    }
    public function broadcastWith(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'color' => $this->colorHex,
            'timestamp' => now()->toISOString(),
        ];
    }
}
