<?php

namespace App\Listeners;

use App\Events\SendCustomNotification;
use App\Notifications\CustomNotification;

class SendCustomNotificationListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\SendCustomNotification  $event
     * @return void
     */
    public function handle(SendCustomNotification $event)
    {
        $event->user->notify(new CustomNotification($event->title, $event->body, $event->colorHex));
    }
}
