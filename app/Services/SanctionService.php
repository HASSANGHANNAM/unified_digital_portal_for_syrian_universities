<?php

namespace App\Services;

use App\DTOs\SanctionDTO;
use App\Repositories\Contracts\UserRepositoryInterface;

class SanctionService
{
     public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
    ) {}

    public function getSanctions(array $data): array
    {
        $message = 'عرض العقوبات المسجلة على الطالب.';
        $code = 200;
        // use App\DTOs\SanctionDTO;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function addSanction(array $data): array
    {
        $message = 'إضافة عقوبة أو ترفيع لطالب.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function updateSanction(array $data, int $sanctionId): array
    {
        $message = 'تعديل عقوبة موجودة.';
        $code = 200;
        $data = array_merge($data, ["sanctionId" => $sanctionId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function deleteSanction(array $data, int $sanctionId): array
    {
        $message = 'حذف عقوبة.';
        $code = 200;
        $data = array_merge($data, ["sanctionId" => $sanctionId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

}
