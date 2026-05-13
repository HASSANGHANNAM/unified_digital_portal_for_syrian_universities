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


class ProfileService
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
        private PersonRepositoryInterface $personRepo,
        private PersonAttachmentRepositoryInterface $personAttachmentRepo,
        private EmailVerificationRepositoryInterface $emailRepo,
    ) {}

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
