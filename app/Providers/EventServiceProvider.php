<?php

namespace App\Providers;

use App\Events\StudentRegistered;
use App\Listeners\SendLoginSuccessNotification;
use App\Listeners\SendStudentRegisteredNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            SendLoginSuccessNotification::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        StudentRegistered::class => [
            SendStudentRegisteredNotification::class,
        ],
        SendUserNotification::class => [
            SendUserNotificationListener::class,
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
