<?php

namespace App\Services;

use App\DTOs\SanctionDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\SanctionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SanctionService
{
     public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private SanctionRepositoryInterface $sanctionRepositoryInterface
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
    //كل العقوبات المسجلة على الطالب
        public function getAllSanctions(): array
    {
        $user = Auth::user();
        $sanctions = $this->sanctionRepositoryInterface->getStudentSanctions($user->id);
        if ($sanctions->isEmpty()) {
            return [
                'data' => [],
                'message' => 'No sanctions found.',
                'code' => 404,
            ];
        }
        $data = $sanctions->map(function ($sanction) {
            return [
                'id' => $sanction->id,
                'type' => $sanction->sanctionType->name ?? '',
                'course' => $sanction->course->name ?? '',
                'status' => $sanction->status,
                'issued_date' => $sanction->issued_date,
                'expiry_date' => $sanction->expiry_date,
            ];
        });
        return [
            'data' => $data,
            'message' => 'Sanctions retrieved successfully.',
            'code' => 200,
        ];
    }

       //تفاصيل كل عقوبة
        public function getSanctionDetails(int $sanctionId): array
    {
        $user = Auth::user();
        $sanction = $this->sanctionRepositoryInterface->getStudentSanctionById($user->id,$sanctionId);
        if (!$sanction) {
            return [
                'data' => [],
                'message' => 'Sanction not found.',
                'code' => 404,
            ];
        }
        return [
            'data' => [
                'id' => $sanction->id,
                'type' => $sanction->sanctionType->name ?? '',
                'reason' => $sanction->sanctionType->reason ?? '',
                'course' => $sanction->course->name ?? '',
                'status' => $sanction->status,
                'issued_date' => $sanction->issued_date,
                'expiry_date' => $sanction->expiry_date,
                'notes' => $sanction->notes,
                'student_response' => $sanction->student_response,
                'staff_response' => $sanction->staff_response,
            ],
            'message' => 'Sanction retrieved successfully.',
            'code' => 200,
        ];
    }
        // للطالب
        public function respondToSanction(int $sanctionId,array $data): array
  {
        $user = Auth::user();
        $sanction = $this->sanctionRepositoryInterface->getStudentSanctionById($user->id, $sanctionId);

        if (!$sanction) {
            return [
                'data' => [],
                'message' => 'Sanction not found.',
                'code' => 404,
            ];
        }
        if ($sanction->student_response) {
            return [
                'data' => [],
                'message' => 'You have already responded to this sanction.',
                'code' => 403,
            ];
        }
        $updated = $this->sanctionRepositoryInterface->updateResponse($sanction->id,[
                'student_response' => $data['student_response'],
            ]);

        if (!$updated) {
            return [
                'data' => [],
                'message' => 'Failed to submit response.',
                'code' => 400,
            ];
        }
        return [
            'data' => [],
            'message' => 'Response submitted successfully.',
            'code' => 200,
        ];
    }


}
