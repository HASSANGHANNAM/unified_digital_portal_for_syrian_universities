<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;

class MaterialService
{
     public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
    ) {}

    public function getMaterials(array $data, int $courseId): array
    {
        $message = 'استعراض المحاضرات والمواد التعليمية لمقرر معين.';
        $code = 200;
        $data = '';
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function uploadMaterial(array $data, int $courseId): array
    {
        $message = 'رفع محاضرة أو ملف تعليمي إلى مقرر معين.';
        $code = 200;
        $data = '';
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function updateMaterial(array $data, int $materialId): array
    {
        $message = 'تعديل محتوى تعليمي موجود.';
        $code = 200;
        $data = '';
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function deleteMaterial(array $data, int $materialId): array
    {
        $message = 'حذف محتوى تعليمي.';
        $code = 200;
        $data = '';
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function downloadMaterial(array $data, int $materialId): array
    {
        $message = 'تحميل ملف المحاضرة.';
        $code = 200;
        $data = '';
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

}
