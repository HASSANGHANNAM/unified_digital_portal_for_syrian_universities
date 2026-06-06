<?php

namespace App\Services;

use App\DTOs\PermissionsListDTO;
use App\DTOs\UserDTO;
use App\DTOs\UserListDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
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
}
