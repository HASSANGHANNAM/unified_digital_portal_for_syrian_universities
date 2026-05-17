<?php

namespace App\Services;

use App\DTOs\ApplicationDTO;
use App\Repositories\Contracts\UserRepositoryInterface;

class AdmissionService
{
      public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
    ) {}

    public function applyForAdmission(array $data): array
    {
        $message = 'تقديم طلب قبول إلكتروني لطالب جديد مع رفع الوثائق.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getMyApplication(array $data): array
    {
        $message = 'عرض حالة طلب القبول للطالب المتقدم.';
        $code = 200;
        // use App\DTOs\ApplicationDTO;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getApplications(array $data): array
    {
        $message = 'قائمة طلبات القبول لمراجعتها (لشؤون الطلاب).';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function reviewApplication(array $data, int $id): array
    {
        $message = 'قبول أو رفض طلب قبول طالب جديد.';
        $code = 200;
        $data = array_merge($data, ["id" => $id]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

}
