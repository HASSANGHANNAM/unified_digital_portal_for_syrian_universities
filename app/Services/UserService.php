<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\DTOs\UserListDTO;
use App\DTOs\UserPermissionsDTO;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
    ) {}

    public function getUsers(): array
    {
        $message = 'عرض قائمة المستخدمين مع إمكانية فلترتهم حسب الدور.';
        $code = 200;
        // use App\DTOs\UserListDTO;
        $data = '';
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function addUser(array $data): array
    {
        $message = 'إضافة مستخدم جديد (دكتور، معيد، إداري، رئيس قسم، عميد، مدير جامعة، وزارة).';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function updateUserRole(array $data, int $id): array
    {
        $message = 'تعديل دور المستخدم.';
        $code = 200;
        $data = array_merge($data, ["id" => $id]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function deleteUser(array $data, int $id): array
    {
        $message = 'حذف مستخدم.';
        $code = 200;
        $data = array_merge($data, ["id" => $id]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getUserPermissions(array $data, int $id): array
    {
        $message = 'عرض صلاحيات مستخدم معين.';
        $code = 200;
        // use App\DTOs\UserPermissionsDTO;
        $data = array_merge($data, ["id" => $id]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function toggleUserActivation(array $data, int $id): array
    {
        $message = 'تفعيل أو تعطيل حساب مستخدم.';
        $code = 200;
        $data = array_merge($data, ["id" => $id]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

}
