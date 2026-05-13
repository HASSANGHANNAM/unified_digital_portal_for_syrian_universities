<?php

namespace App\Listeners;

use App\Events\SendLoginSuccessNotification;
use App\Notifications\LoginSuccessNotification;

class SendLoginSuccessNotificationListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\SendLoginSuccessNotification  $event
     * @return void
     */
    public function handle(SendLoginSuccessNotification $event)
    {
        $event->user->notify(new LoginSuccessNotification($event->message));
    }
}
