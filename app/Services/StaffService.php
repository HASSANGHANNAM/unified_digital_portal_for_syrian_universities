<?php

namespace App\Services;

use App\DTOs\StaffListDTO;

use App\Repositories\Contracts\StaffRepositoryInterface;

class StaffService
{
    public function __construct(private StaffRepositoryInterface $staffRepositoryInterface) {}
    public function getStaff(array $filters, int $perPage = 15): array
    {
        $staff = $this->staffRepositoryInterface->getStaff($filters, $perPage);

        $data = collect($staff->items())
            ->map(fn($item) => StaffListDTO::fromModel($item)->toArray())
            ->values()
            ->toArray();

        return [
            'data' => [
                'staffs' => $data,
                'meta' => [
                    'current_page' => $staff->currentPage(),
                    'per_page' => $staff->perPage(),
                    'total' => $staff->total(),
                    'last_page' => $staff->lastPage(),
                ],
            ],
            'message' => 'قائمة الموظفين.',
            'code' => 200,
        ];
    }
}
