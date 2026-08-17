<?php

namespace App\Services;

use App\DTOs\SanctionDTO;
use App\DTOs\SanctionTypeDTO;
use App\DTOs\SanctionTypeListDTO;
use App\DTOs\StudentSanctionDTO;
use App\Models\SanctionType;
use App\Repositories\Contracts\SanctionRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Traits\TokenDataTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Throwable;

class SanctionService
{
    use TokenDataTrait;

    public function __construct(
        private SanctionRepositoryInterface $sanctionRepositoryInterface,
        private ?UserRepositoryInterface $userRepositoryInterface = null
    ) {}

    public function listSanctionTypes(array $filters = [], int $perPage = 15): array
    {
        try {
            $paginator = $this->sanctionRepositoryInterface->getPaginatedWithFilters($filters, $perPage);
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
            $created = $this->sanctionRepositoryInterface->createSanctionType($data);
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

            $sanction = $this->sanctionRepositoryInterface->create($payload);
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
    //كل العقوبات المسجلة على الطالب
    public function getAllSanctions(): array
    {
        $user = Auth::user();
        $perPage = request()->input('per_page', 10);

        $sanctions = $this->sanctionRepositoryInterface->getStudentSanctions($this->getStudentId(), $perPage);

        if ($sanctions->isEmpty()) {
            return [
                'data' => [],
                'message' => 'No sanctions found.',
                'code' => 404,
            ];
        }

        $data = collect($sanctions->items())->map(function ($sanction) {
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
            'data' => [
                'sanctions' => $data,
                'meta' => [
                    'current_page' => $sanctions->currentPage(),
                    'last_page'    => $sanctions->lastPage(),
                    'per_page'     => $sanctions->perPage(),
                    'total'        => $sanctions->total(),
                ]
            ],
            'message' => 'Sanctions retrieved successfully.',
            'code' => 200,
        ];
    }

    //تفاصيل كل عقوبة
    public function getSanctionDetails(int $sanctionId): array
    {
        $user = Auth::user();
        $sanction = $this->sanctionRepositoryInterface->getStudentSanctionById($this->getStudentId(), $sanctionId);
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

    public function getStudentSanctions(array $validated): array
    {
        $studentId = (int) ($validated['student_id'] ?? 0);
        $perPage = (int) ($validated['per_page'] ?? 15);
        $page = (int) ($validated['page'] ?? 1);
        $sanctions = $this->sanctionRepositoryInterface->getSanctionsByStudent($studentId, $perPage, $page);

        if (!$sanctions || $sanctions->isEmpty()) {
            return [
                'data' => [
                    'data' => [],
                    'meta' => [
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => $perPage,
                        'total' => 0,
                    ],
                ],
                'message' => 'تم جلب العقوبات بنجاح',
                'code' => 200,
            ];
        }

        $items = $sanctions->getCollection()->map(function ($sanction) {
            return StudentSanctionDTO::fromModel($sanction)->toArray();
        })->values()->all();

        return [
            'data' => [
                'data' => $items,
                'meta' => [
                    'current_page' => $sanctions->currentPage(),
                    'last_page' => $sanctions->lastPage(),
                    'per_page' => $sanctions->perPage(),
                    'total' => $sanctions->total(),
                ],
            ],
            'message' => 'تم جلب العقوبات بنجاح',
            'code' => 200,
        ];
    }

    // للطالب
    public function respondToSanction(int $sanctionId, array $data): array
    {
        $student = Auth::user();
        $sanction = $this->sanctionRepositoryInterface->getStudentSanctionById($this->getStudentId(), $sanctionId);

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
        $updated = $this->sanctionRepositoryInterface->updateResponse($sanction->id, [
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
