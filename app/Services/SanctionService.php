<?php

namespace App\Services;

use App\DTOs\SanctionDTO;
use App\DTOs\SanctionTypeDTO;
use App\DTOs\SanctionTypeListDTO;
use App\Models\SanctionType;
use App\Repositories\SanctionRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Traits\TokenDataTrait;
use Carbon\Carbon;
use Throwable;

class SanctionService
{
    use TokenDataTrait;

    public function __construct(
        private SanctionRepository $sanctionRepository,
        private UserRepositoryInterface $userRepositoryInterface
    ) {}

    public function listSanctionTypes(array $filters = [], int $perPage = 15): array
    {
        try {
            $paginator = $this->sanctionRepository->getPaginatedWithFilters($filters, $perPage);
            return [
                'data' => SanctionTypeListDTO::fromPaginator($paginator),
                'message' => 'قائمة أنواع العقوبات',
                'code' => 200,
            ];
        } catch (Throwable $th) {
            return ['data' => [], 'message' => $th->getMessage(), 'code' => 500];
        }
    }

    public function createSanctionType(array $data): array
    {
        try {
            $created = $this->sanctionRepository->createSanctionType($data);
            return ['data' => SanctionTypeDTO::fromModel($created)->toArray(), 'message' => 'تم إضافة نوع العقوبة', 'code' => 201];
        } catch (Throwable $th) {
            return ['data' => [], 'message' => $th->getMessage(), 'code' => 400];
        }
    }

    public function getSanctions(array $data): array
    {
        $message = 'عرض العقوبات المسجلة على الطالب.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function storeSanction(array $data): array
    {
        try {
            $staffId = $this->getStaffId();
            if (!$staffId) {
                return ['data' => [], 'message' => 'Unauthorized staff', 'code' => 403];
            }

            $sanctionType = SanctionType::find($data['sanction_type_id']);
            if (!$sanctionType) {
                return ['data' => [], 'message' => 'Sanction type not found', 'code' => 404];
            }

            $issuedDate = $data['issued_date'];
            $expiryDate = $data['expiry_date'] ?? null;
            if (empty($expiryDate)) {
                $years = (int) ($sanctionType->years ?? 0);
                $months = (int) ($sanctionType->months ?? 0);
                $days = (int) ($sanctionType->days ?? 0);
                $expiryDate = Carbon::parse($issuedDate)->addYears($years)->addMonths($months)->addDays($days)->toDateString();
            }

            $payload = [
                'sanction_type_id' => $data['sanction_type_id'],
                'status' => 'ongoing',
                'issued_date' => $issuedDate,
                'expiry_date' => $expiryDate,
                'notes' => $data['notes'] ?? null,
                'student_response' => null,
                'staff_response' => null,
                'student_id' => $data['student_id'],
                'staff_id' => $staffId,
                'course_id' => $data['course_id'] ?? null,
            ];

            $sanction = $this->sanctionRepository->create($payload);
            return ['data' => SanctionDTO::fromModel($sanction)->toArray(), 'message' => 'تم إضافة العقوبة بنجاح', 'code' => 201];
        } catch (Throwable $th) {
            return ['data' => [], 'message' => $th->getMessage(), 'code' => 400];
        }
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
