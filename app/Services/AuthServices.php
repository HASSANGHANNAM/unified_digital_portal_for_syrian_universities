<?php

namespace App\Services;

use App\Events\SendLoginSuccessNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\EmailVerificationRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\Student;
use App\DTOs\LoginDTO;
use App\DTOs\UserDTO;
use App\Events\SendCustomNotification;

class AuthServices
{

    public function __construct(
        private UserRepositoryInterface $userRepo,
        private PersonRepositoryInterface $personRepository,
        private EmailVerificationRepositoryInterface $emailRepo,
        private TokenServices $tokenService,
    ) {}

    public function login($request): array
    {
        $user = $this->userRepo->findByUserName($request['username']);
        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['data is incorrect'],
            ]);
        }
        if (!$user->email_verified_at) {
            throw new \Exception('Email must be verified before logging in');
        }
        if (!Hash::check($request['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['data is incorrect.'],
            ]);
        }
        $user->refresh();
        $loginDto = LoginDTO::fromServiceData($this->tokenService->createAuthTokens($user), $user);
        return [
            'data' => $loginDto->toArray(),
            'message' => 'Login successful',
            'code' => 200
        ];
    }

    public function getProfile(): array
    {
        $user = Auth::user();

        $student = Student::where('person_id', $user->person_id)->first();

        return [
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->person?->full_name,
                'email' => $user->email,
                'phone' => $user->person?->phone,
                'address' => $user->person?->address,
                'study_info' => 'السنة ' . $student?->current_year . ' - ' . $student?->major,
            ],
            'message' => 'User profile retrieved successfully',
            'code' => 200,
        ];
    }

    public function editProfile($request): array
    {
        $user = Auth::user();

        $oldEmail = $user->email;

        $user = $this->userRepo->update(
            $user,
            $request->only([
                'username',
                'email',
            ])
        );
        $person = $this->personRepository->update(
            $user->person,
            $request->only([
                'full_name',
                'phone',
                'address',
            ])
        );
        $message = 'Profile updated successfully';

        if ($request->filled('email') && $request->email !== $oldEmail) {
            $user->update([
                'email_verified_at' => null,
            ]);
            $this->emailRepo->sendCode($user);
            $message = 'Profile updated successfully. Please verify your new email address.';
        }
        $student = Student::where('person_id', $user->person_id)->first();

        return [
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $person->full_name,
                'email' => $user->email,
                'phone' => $person->phone,
                'address' => $person->address,
                'study_info' => 'السنة ' . $student?->current_year . ' - ' . $student?->major,
            ],
            'message' => $message,
            'code' => 200,
        ];
    }
    //تغيير كلمة اذا كان الطالب متذكرها
    public function changePassword($request): array
    {
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            throw new \Exception('Old password is incorrect.');
        }
        if ($request->old_password === $request->new_password) {
            throw new \Exception('The new password must be different from the current password.');
        }
        $this->userRepo->changePassword(
            $user,
            $request->new_password
        );
        return [
            'data' => [],
            'message' => 'Password changed successfully.',
            'code' => 200,
        ];
    }
    //اذا نسي الطالب كلمة السر يضغط forgotPassword -> يوصله كود للتاكد
    public function forgotPassword(): array
    {
        $user = auth()->user();
        if (!$user) {
            throw new \Exception('User not found.');
        }
        if (!$user->email) {
            throw new \Exception('No email is associated with this account.');
        }
        $this->emailRepo->sendCode($user);
        return [
            'data' => [],
            'message' => 'A verification code has been sent to your email.',
            'code' => 200,
        ];
    }
    // يضع كود التحقق من كلمة السر هنا
    public function verifyResetCode($request): array
    {
        $user = auth()->user();
        if (!$user->email) {
            throw new \Exception('No email is associated with this account.');
        }
        $ok = $this->emailRepo->verifyResetCode(
            $user,
            $request->code
        );

        if (!$ok) {
            throw new \Exception('Invalid or expired verification code.');
        }
        return [
            'data' => [
                'verified' => true,
            ],
            'message' => 'Verification code is valid.',
            'code' => 200,
        ];
    }
    // يضع كلمة السر الجديدة هنا بعد التحقق من الكود
    public function resetPassword($request): array
    {
        $user = auth()->user();

        if (!$this->emailRepo->canResetPassword($user)) {
            throw new \Exception('You must verify the OTP first.');
        }

        $this->userRepo->changePassword(
            $user,
            $request->new_password
        );
        $this->emailRepo->clearResetCode($user);
        return [
            'data' => [],
            'message' => 'Password reset successfully.',
            'code' => 200,
        ];
    }



    public function logout($user): array
    {
        event(new SendCustomNotification(
            $user,
            'تسجيل خروج',
            'لقد سجلت الخروج بنجاح',
            'WARNING'
        ));
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
        $tokenData = $this->tokenService->refreshTokens(
            $request['refresh_token']
        );

        $loginDto = LoginDTO::fromServiceData(
            $tokenData,
            $tokenData['user']
        );

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
