<?php

namespace App\Repositories;

use App\Models\EmailVerification;
use App\Models\User;
use App\Repositories\Contracts\EmailVerificationRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EmailVerificationRepository implements EmailVerificationRepositoryInterface
{
    public function sendCode(User $user): void
    {
        $code = rand(100000, 999999);

        EmailVerification::create([
            'user_id'     => $user->id,
            'code'        => $code,
            'expires_at'  => now()->addMinutes(10),
        ]);

        Mail::raw("كود التحقق الخاص بك هو: {$code}", function ($message) use ($user) {
            $message->to($user->Email)->subject('رمز التحقق لحسابك');
        });
    }

    public function resendCode(string $Email): string
    {
        $user = User::where('Email', $Email)->first();
        if (!$user) {
            throw new \Exception('البريد الإلكتروني غير موجود');
        }

        if ($user->email_verified_at) {
            throw new \Exception('تم تفعيل البريد الإلكتروني مسبقًا');
        }

        $last = EmailVerification::where('user_id', $user->id)
            ->where('is_verified', false)
            ->latest()
            ->first();

        if ($last && $last->created_at->diffInSeconds(now()) < 60) {
            throw new \Exception('يمكنك إعادة الإرسال بعد دقيقة واحدة فقط');
        }

        EmailVerification::where('user_id', $user->id)
            ->where('is_verified', false)
            ->delete();

        $code = rand(100000, 999999);

        EmailVerification::create([
            'user_id'    => $user->id,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::raw("رمز التحقق الجديد الخاص بك هو: {$code}", function ($message) use ($user) {
            $message->to($user->Email)->subject('رمز التحقق الجديد لحسابك');
        });

        return 'تم إرسال رمز تحقق جديد إلى بريدك الإلكتروني';
    }

    public function verify(User $user, string $code): bool
    {
        $record = EmailVerification::where('user_id', $user->id)
            ->where('code', $code)
            ->where('is_verified', false)
            ->first();

        if (!$record) {
            return false;
        }

        if ($record->expires_at->lt(now())) {
            return false;
        }

        $record->update(['is_verified' => true]);
        $user->update(['email_verified_at' => now()]);


        $user->refresh();

        return true;
    }
}
