<?php

namespace App\Providers;

use App\Events\SendCustomNotification;
use App\Events\SendLoginSuccessNotification;
use App\Events\SendUserNotification;
use App\Events\StudentRegistered;
use App\Listeners\SendCustomNotificationListener;
use App\Listeners\SendLoginSuccessNotificationListener;
use App\Listeners\SendStudentRegisteredNotificationListener;
use App\Listeners\SendUserNotificationListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SendLoginSuccessNotification::class => [
            SendLoginSuccessNotificationListener::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        StudentRegistered::class => [
            SendStudentRegisteredNotificationListener::class,
        ],
        SendUserNotification::class => [
            SendUserNotificationListener::class,
        ],
        SendCustomNotification::class => [
            SendCustomNotificationListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
