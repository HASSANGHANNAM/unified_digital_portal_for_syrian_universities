<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendUserNotification
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
}