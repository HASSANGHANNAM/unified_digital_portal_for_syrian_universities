<?php

namespace App\Services;

use App\Events\SendLoginSuccessNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\EmailVerificationRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\DTOs\LoginDTO;
use App\DTOs\UserDTO;

class AuthServices
{

    public function __construct(
        private UserRepositoryInterface $userRepo,
        private EmailVerificationRepositoryInterface $emailRepo,
        private TokenServices $tokenService,
    ) {}

    public function login($request): array
    {
        $user = $this->userRepo->findByUserName($request['username']);
        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['username is incorrect.'],
            ]);
        }
        if (!$user->email_verified_at) {
            throw new \Exception('Email must be verified before logging in');
        }
        if (!Hash::check($request['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['password is incorrect.'],
            ]);
        }
        $user->refresh();
        $loginDto = LoginDTO::fromServiceData($this->tokenService->createAuthTokens($user), $user);
        event(new SendLoginSuccessNotification($user, 'Login successful'));
        return [
            'data' => $loginDto->toArray(),
            'message' => 'Login successful',
            'code' => 200
        ];
    }

    public function getProfile(): array
    {
        $user = Auth::user();
        $userDto = UserDTO::fromModel($user);

        return [
            'data' => $userDto->toArray(),
            'message' => 'User profile retrieved successfully',
            'code' => 200
        ];
    }
    public function logout($user): array
    {
        $this->tokenService->revokeAllTokens($user);
        $data = [];
        $message = 'Logged out successfully';
        $code = 200;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code
        ];
    }
    public function refreshToken($request): array
    {
        $tokenData = $this->tokenService->refreshTokens($request['refresh_token']);
        $user = auth()->user();
        $loginDto = LoginDTO::fromServiceData($tokenData, $user);
        return [
            'data' => $loginDto->toArray(),
            'message' => 'Token refreshed successfully',
            'code' => 200
        ];
    }

    public function resendCode($Email): array
    {
        $data = $this->emailRepo->resendCode($Email);
        $message = 'Resend successfully';

        return [
            'data'    => $data,
            'message' => $message,
            'code'    => 200
        ];
    }

    public function verifyCode($request): array
    {
        $user = auth()->user();

        if (!$user->email) {
            throw new \Exception('لم يتم إعداد البريد الإلكتروني بعد');
        }

        $ok = $this->emailRepo->verify($user, $request['code']);

        if (!$ok) {
            throw new \Exception('رمز التحقق غير صالح أو منتهي');
        }

        $user->refresh();

        $userDto = UserDTO::fromModel($user);

        $message = 'تم توثيق البريد الإلكتروني بنجاح';
        return [
            'data' => [
                'verified' => true,
                'user' => $userDto->toArray()
            ],
            'message' => $message,
            'code' => 200
        ];
    }

    private function storeIdFile($file, $type): string
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $folder = 'users/id_cards/' . date('Y/m');
        $fullPath = $folder . '/' . $type . '_' . $fileName;
        Storage::disk('secure_documents')->put(
            $fullPath,
            file_get_contents($file->getRealPath())
        );
        return $fullPath;
    }
}
