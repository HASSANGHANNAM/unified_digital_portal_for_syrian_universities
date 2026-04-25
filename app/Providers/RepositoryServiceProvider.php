<?php

namespace App\Providers;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\EmailVerificationRepositoryInterface;
use App\Repositories\EmailVerificationRepository;
use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\NotificationRepository;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(EmailVerificationRepositoryInterface::class, EmailVerificationRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }

    public function boot()
    {
        //
    }
}
