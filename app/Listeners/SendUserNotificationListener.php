<?php

namespace App\Listeners;

use App\Events\SendUserNotification;
use App\Notifications\UserNotification;

class SendUserNotificationListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\SendUserNotification  $event
     * @return void
     */
    public function handle(SendUserNotification $event)
    {
        $event->user->notify(new UserNotification($event->message));
    }
}
