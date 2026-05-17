<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\NotificationService::class);
    }

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('premium-api', function (Request $request) {
            $user = $request->user();
            $key = $user ? $user->id : $request->ip();
            if ($user && method_exists($user, 'hasRole') && $user->hasRole('premium')) {
                return Limit::perMinute(300)->by($key);
            }
            return Limit::perMinute(60)->by($key);
        });

        RateLimiter::for('heavy', function (Request $request) {
            $user = $request->user();
            $key = $user ? $user->id : $request->ip();
            return Limit::perMinute(10)->by($key);
        });
    }
}
