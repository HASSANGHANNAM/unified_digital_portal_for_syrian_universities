<?php

namespace App\Listeners;

use App\Notifications\CustomNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

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
        Log::info('SendUserNotificationListener: Sending notification to user '.$event->user->id);
        $event->user->notify(new CustomNotification($event->message));
    }
}