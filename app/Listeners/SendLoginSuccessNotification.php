<?php

namespace App\Listeners;

use App\Notifications\LoginSuccessNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendLoginSuccessNotification
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        if ($user) {
            Log::info('SendLoginSuccessNotification: Handling login event for user '.$user->id);
            $user->notify(new LoginSuccessNotification(
                'مرحباً ' . ($user->username ?? 'المستخدم') . '، تم تسجيل دخولك بنجاح'
            ));

            Log::info('Login success notification sent', [
                'user_id' => $user->id,
                'username' => $user->username,
            ]);
        }
    }
}