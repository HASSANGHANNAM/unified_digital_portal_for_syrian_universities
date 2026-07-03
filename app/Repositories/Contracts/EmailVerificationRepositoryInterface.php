<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface EmailVerificationRepositoryInterface
{
    public function sendCode(User $user): void;

    public function resendCode(string $email): string;

    public function verify(User $user, string $code): bool;

    public function verifyResetCode(User $user, string $code): bool;

    public function canResetPassword(User $user): bool;

    public function clearResetCode(User $user): void;
}
