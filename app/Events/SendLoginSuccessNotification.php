<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendLoginSuccessNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $user
     * @param  string  $message
     * @return void
     */
    public function __construct($user, string $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return ['private-App.Models.User.' . $this->user->id];
    }

    public function broadcastAs()
    {
        return 'SendLoginSuccessNotification';
    }
}
