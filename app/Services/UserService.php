<?php

namespace App\Services;

use App\DTOs\PermissionsListDTO;
use App\DTOs\UserDTO;
use App\DTOs\UserListDTO;
use App\DTOs\SignatureDTO;
use App\DTOs\SignatureDTO2;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\UserSignatureRepositoryInterface;
use App\Notifications\SignatureUploadedNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Throwable;
use App\Services\MediaService;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private MediaService $mediaService,
        private UserSignatureRepositoryInterface $userSignatureRepository,
    ) {}

    public function listUsers(array $filters = []): array
    {
        $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 15;
        $users = $this->userRepositoryInterface->getUsersWithFilters($filters, $perPage);
        $data = [
            'data' => collect($users->items())->map(fn(User $user) => UserListDTO::fromModel($user)->toArray())->toArray(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ];

        return [
            'data' => $data,
            'message' => 'تمت جلب قائمة المستخدمين بنجاح.',
            'code' => 200,
        ];
    }

    public function createUser(array $data): array
    {
        $role = Role::where('name', $data['role_name'])->first();
        if (! $role) {
            throw new \RuntimeException('الدور المطلوب غير موجود.');
        }

        return DB::transaction(function () use ($data, $role) {
            $user = $this->userRepositoryInterface->create($data);
            $this->userRepositoryInterface->assignRole($user, $role->name);
            $user->load(['roles', 'person']);

            return [
                'data' => UserDTO::fromModel($user)->toArray(),
                'message' => 'تم إنشاء المستخدم وربطه بالدور بنجاح.',
                'code' => 201,
            ];
        });
    }

    public function updateRole(array $data, int $id): array
    {
        $user = $this->userRepositoryInterface->findById($id);
        if (! $user) {
            throw new \RuntimeException('المستخدم غير موجود.');
        }

        $role = Role::where('name', $data['role_name'])->first();
        if (! $role) {
            throw new \RuntimeException('الدور المطلوب غير موجود.');
        }

        $user->syncRoles([$role->name]);
        $user->load(['roles', 'person']);

        return [
            'data' => UserDTO::fromModel($user)->toArray(),
            'message' => 'تم تحديث دور المستخدم بنجاح.',
            'code' => 200,
        ];
    }

    public function deleteUser(array $data, int $id): array
    {
        $user = $this->userRepositoryInterface->findById($id);
        if (! $user) {
            throw new \RuntimeException('المستخدم غير موجود.');
        }

        if ($user->hasRole('student')) {
            throw new \RuntimeException('لا يمكن حذف مستخدم من نوع طالب.');
        }

        $user->delete();

        return [
            'data' => ['id' => $id],
            'message' => 'تم حذف المستخدم بنجاح.',
            'code' => 200,
        ];
    }

    public function getPermissions(array $data, int $id): array
    {
        $user = $this->userRepositoryInterface->findById($id);
        if (! $user) {
            throw new \RuntimeException('المستخدم غير موجود.');
        }

        $permissions = $user->getAllPermissions()->pluck('name')->unique()->values()->toArray();

        return [
            'data' => PermissionsListDTO::fromArray($permissions)->toArray(),
            'message' => 'تمت جلب الصلاحيات بنجاح.',
            'code' => 200,
        ];
    }

    public function toggleActivation(array $data, int $id): array
    {
        $user = $this->userRepositoryInterface->findById($id);
        if (! $user) {
            throw new \RuntimeException('المستخدم غير موجود.');
        }

        $newStatus = $data['status'] ?? ($user->status === 'active' ? 'inactive' : 'active');
        $user->update(['status' => $newStatus]);

        return [
            'data' => ['status' => $newStatus],
            'message' => 'تم تحديث حالة التفعيل بنجاح.',
            'code' => 200,
        ];
    }

    public function uploadSignature(array $data): array
    {
        try {
            $userId = auth()->id();
            if (!$userId) {
                return [
                    'data' => null,
                    'message' => 'يجب تسجيل الدخول أولاً',
                    'code' => 401,
                ];
            }

            // 1. استخراج البيانات من Base64
            $extracted = $this->mediaService->extractImageFromBase64($data['signature']);

            // 2. توليد اسم ملف فريد (مع البادئة والتاريخ كما كان)
            $filename = $this->mediaService->generateUniqueFilename(
                $extracted['extension'],
                'sig'
            );

            // 3. المسار الكامل (نفس المسار القديم)
            $userFolder = 'private/signatures/' . $userId;
            $path = $userFolder . '/' . $filename;

            // 4. حفظ الملف في التخزين
            Storage::disk('local')->put($path, $extracted['binary']);

            // 5. التحقق من الحفظ
            if (!Storage::disk('local')->exists($path)) {
                return [
                    'data' => null,
                    'message' => 'فشل حفظ ملف التوقيع على الخادم',
                    'code' => 500,
                ];
            }

            // 6. إنشاء سجل في قاعدة البيانات (مع UUID)
            $uuid = (string) Str::uuid();
            $signature = $this->userSignatureRepository->create($userId, $uuid, $path);

            // 🔥 7. تحديث السجل بإضافة المسار الكامل

            // 8. (اختياري) إرسال إشعار للمستخدم
            $user = $signature->user;
            if ($user) {
                // إشعار
            }

            // 9. تجهيز الـ DTO للإرجاع
            $dto = new SignatureDTO(
                id: $signature->id,
                userId: $signature->user_id,
                uuid: $signature->signature_uuid,
                createdAt: $signature->created_at->toISOString(),
            );

            return [
                'data' => $dto->toArray(),
                'message' => 'تم رفع التوقيع بنجاح',
                'code' => 201,
            ];
        } catch (Throwable $th) {
            return [
                'data' => null,
                'message' => 'حدث خطأ أثناء رفع التوقيع: ' . $th->getMessage(),
                'code' => 400,
            ];
        }
    }

    public function getMySignature(): array
    {
        $user = auth()->user();
        $userId = $user->id;

        if (!$userId) {
            return [
                'signature' => null,
                'is_sig'    => false,
                'message'   => 'يجب تسجيل الدخول أولاً',
                'code'      => 401,
            ];
        }

        $signature = $this->userSignatureRepository->getLatestForUser($userId);
        $college_id = null;
        if ($user->hasRole('StudentAffairs') || $user->hasRole('Examination')) {
            $staff = \App\Models\Staff::where('person_id', $user->person_id)->first();
            if ($staff && $staff->department_id) {
                $department = \App\Models\Department::find($staff->department_id);
                if ($department && $department->college_id) {
                    $college_id = $department->college_id;
                }
            }
        } elseif ($user->hasRole('Dean')) {
            $collegeId = DB::table('college_deans')
                ->join('doctors', 'college_deans.doctor_id', '=', 'doctors.id')
                ->join('users', 'doctors.person_id', '=', 'users.person_id')
                ->where('users.id', auth()->id())
                ->value('college_deans.college_id');
            if ($collegeId) {
                $college_id = $collegeId;
            }
        }
        if (!$signature) {
            $data['signature'] = null;
            $data['is_sig'] = false;
            $data['college_id'] = $college_id;
            return [
                'data'    => $data,
                'message'   => 'لا يوجد توقيع لهذا المستخدم',
                'code'      => 200,
            ];
        }
        $data['signature'] = SignatureDTO2::fromModel($signature);
        $data['is_sig'] = true;
        $data['college_id'] = $college_id;
        return [

            'data'    => $data,
            'message'   => 'تم جلب التوقيع بنجاح',
            'code'      => 200,
        ];
    }
}
