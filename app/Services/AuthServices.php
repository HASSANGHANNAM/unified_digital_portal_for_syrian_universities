<?php

namespace App\Services;

use App\Events\SendUserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Login;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\PersonAttachmentRepositoryInterface;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\EmailVerificationRepositoryInterface;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Notifications\CustomNotification;
use App\Notifications\LoginSuccessNotification;

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
        $user->notify(new LoginSuccessNotification('succsess Login notify'));
        // event(new SendUserNotification($user, 'succsess Login'));
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
        // $this->notificationService->send(
        //     $user,
        //     'تسجيل خروج',
        //     'تم تسجيل الخروج من الحساب',
        //     'logout'
        // );
        $data = [];
        $message = 'Logged out successfully';
        $code = 200;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code
        ];
    }

    public function setupAccount(User $user, array $data): array
    {

        $this->userRepo->update($user, [
            'email' => $data['email'],
            'new_password' => Hash::make($data['new_password']),
        ]);

        $this->emailRepo->sendCode($user);

        $message = 'تم تحديث بياناتك بنجاح';
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

    public function uploadDocument(User $user, $request): array
    {
        if (!$user->person_id) {
            throw new \Exception('يجب إكمال بياناتك أولاً');
        }

        $file = $request->file('file');

        if (!$file || !$file->isValid()) {
            throw new \Exception('الملف غير صالح أو لم يتم تحميله بشكل صحيح');
        }

        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $folder = implode('/', [
            'persons',
            $user->person_id,
            'attachments',
            date('Y'),
            date('m'),
            date('d'),
        ]);

        $path = $file->storeAs($folder, $fileName, 'public');

        $attachment = $this->personAttachmentRepo->create([
            'name' => $request->name,
            'path' => $path,
            'person_id' => $user->person_id,
        ]);

        return [
            'data' => [
                'id' => $attachment->id,
                'name' => $attachment->name,
                'path' => $attachment->path,
                'url' => Storage::disk('public')->url($attachment->path),
            ],
            'message' => 'تم رفع الملف بنجاح',
            'code' => 200,
        ];
    }

    public function completeProfile(User $user, array $data): array
    {
        if ($user->person) {
            $this->personRepo->update($user->person, $data);

            return [
                'data' => [],
                'message' => 'تم تحديث البيانات بنجاح',
                'code' => 200
            ];
        }

        $person = $this->personRepo->create([
            'id' => (string) Str::uuid(),
            'full_name' => $data['full_name'],
            'phone' => $data['phone'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'national_number' => $data['national_number'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        $this->userRepo->update($user, [
            'person_id' => $person->id,
        ]);
        $user->refresh();

        return [
            'data' => [],
            'message' => 'تم إكمال البيانات بنجاح',
            'code' => 200
        ];
    }

    public function submit(User $user): array
    {

        if (!$user->person_id) {
            throw new \Exception('يجب إكمال البيانات الشخصية أولاً');
        }
        $user->load('person.attachments');

        if ($user->person->attachments->isEmpty()) {
            throw new \Exception('يجب رفع الوثائق أولاً');
        }
        if ($user->status === 'pending') {
            throw new \Exception('طلبك قيد المراجعة بالفعل');
        }
        if ($user->status === 'active') {
            throw new \Exception('الحساب مفعل بالفعل');
        }

        $this->userRepo->update($user, [
            'status' => 'pending'
        ]);

        return [
            'data' => [],
            'message' => 'تم إرسال طلبك بنجاح وهو قيد المراجعة',
            'code' => 200
        ];
    }
}
