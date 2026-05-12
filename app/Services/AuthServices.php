<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\PersonAttachmentRepositoryInterface;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\EmailVerificationRepositoryInterface;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\User;


class AuthServices
{

    public function __construct(
        private UserRepositoryInterface $userRepo,
        private PersonRepositoryInterface $personRepo,
        private PersonAttachmentRepositoryInterface $personAttachmentRepo,
        private EmailVerificationRepositoryInterface $emailRepo,
        private TokenServices $tokenService,
        private NotificationService $notificationService
    ) {}

    public function registerUser($request): array
    {
        return DB::transaction(function () use ($request) {
            if (isset($request['IdFrontFace']) && $request['IdFrontFace']->isValid()) {
                $idFrontPath = $this->storeIdFile($request['IdFrontFace'], 'front');
                $request['IdFrontFace'] = $idFrontPath;
            }
            if (isset($request['IdBackFace']) && $request['IdBackFace']->isValid()) {
                $idBackPath = $this->storeIdFile($request['IdBackFace'], 'back');
                $request['IdBackFace'] = $idBackPath;
            }
            $user = $this->userRepo->create($request);
            $this->userRepo->assignRole($user, 'user');
            $this->emailRepo->sendCode($user);
            // notification
            $this->notificationService->send(
                $user,
                'تم إنشاء حساب مريض',
                "مرحباً {$user->FirstnameAr}، تم إنشاء حسابك بنجاح",
                'user_created'
            );
            $data = $this->tokenService->createAuthTokens($user);
            $code = 200;
            $message = 'User created successfully!';
            return ['data' => $data, 'message' => $message, 'code' => $code];
        });
    }

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
        $data = $this->tokenService->createAuthTokens($user);
        //notification
        // $this->notificationService->send(
        //     $user,
        //     'تسجيل دخول',
        //     'تم تسجيل الدخول بنجاح',
        //     'login'
        // );
        $message = 'Login successful';
        $code = 200;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code
        ];
    }

    public function getProfile(): array
    {
        $data = $this->userRepo->getProfile(Auth::user());
        $code = 200;
        $message = 'User profil get successfully!';
        return ['data' => $data, 'message' => $message, 'code' => $code];
    }
    public function logout($user): array
    {
        $this->tokenService->revokeAllTokens($user);
        //notification
        $this->notificationService->send(
            $user,
            'تسجيل خروج',
            'تم تسجيل الخروج من الحساب',
            'logout'
        );
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
        $data = $this->tokenService->refreshTokens($request['refresh_token']);
        $message = 'Token refreshed successfully';
        $code = 200;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code
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
        $message = 'تم توثيق البريد الإلكتروني بنجاح';
        return [
            'data'    => [],
            'message' => $message,
            'code'    => 200
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







    // public function updateIdFiles($userId, array $fileData): void
    // {
    //     try {
    //         $user = $this->user->findOrFail($userId);
    //         $oldFiles = []; // لتخزين مسارات الملفات القديمة

    //         // معالجة الوجه الأمامي
    //         if (isset($fileData['IdFrontFace']) && $fileData['IdFrontFace']->isValid()) {
    //             $oldFiles['front'] = $user->IdFrontFace;
    //             $user->IdFrontFace = $this->storeIdFile($fileData['IdFrontFace'], 'front');
    //         }

    //         // معالجة الوجه الخلفي
    //         if (isset($fileData['IdBackFace']) && $fileData['IdBackFace']->isValid()) {
    //             $oldFiles['back'] = $user->IdBackFace;
    //             $user->IdBackFace = $this->storeIdFile($fileData['IdBackFace'], 'back');
    //         }

    //         // حفظ التغييرات في الداتابيز
    //         $user->save();

    //         // حذف الملفات القديم بعد التأكد من حفظ الجديد
    //         $this->deleteOldFiles($oldFiles);
    //     } catch (\Exception $e) {
    //         // في حالة خطأ، حذف الملفات الجديدة التي تم رفعها
    //         $this->rollbackNewFiles($user, $fileData);
    //         throw new \Exception("فشل في تحديث ملفات الهوية: " . $e->getMessage());
    //     }
    // }

    // private function deleteOldFiles(array $oldFiles): void
    // {
    //     foreach ($oldFiles as $oldPath) {
    //         if ($oldPath && Storage::disk('secure_documents')->exists($oldPath)) {
    //             Storage::disk('secure_documents')->delete($oldPath);
    //         }
    //     }
    // }

    // private function rollbackNewFiles(User $user, array $fileData): void
    // {
    //     // حذف الملفات الجديدة في حالة فشل العملية
    //     if (isset($fileData['IdFrontFace']) && $user->IdFrontFace) {
    //         Storage::disk('secure_documents')->delete($user->IdFrontFace);
    //     }
    //     if (isset($fileData['IdBackFace']) && $user->IdBackFace) {
    //         Storage::disk('secure_documents')->delete($user->IdBackFace);
    //     }
    // }
    // public function getIdFilePath($userId, $type)
    // {
    //     $user = $this->user->findOrFail($userId);
    //     return $type === 'front' ? $user->IdFrontFace : $user->IdBackFace;
    // }
}
