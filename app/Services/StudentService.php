<?php

namespace App\Services;

use App\DTOs\CollegeStudentDTO;
use App\Repositories\Contracts\StudentRepositoryInterface;

class StudentService
{
    public function __construct(private StudentRepositoryInterface $studentRepositoryInterface) {}

    public function getCollegeStudents(array $validated): array
    {
        $collegeId = (int) ($validated['college_id'] ?? 0);
        $perPage = (int) ($validated['per_page'] ?? 15);
        $page = (int) ($validated['page'] ?? 1);
        $students = $this->studentRepositoryInterface->getStudentsByCollege($collegeId, $perPage, $page);

        if (!$students || $students->isEmpty()) {
            return [
                'data' => [
                    [],
                    'meta' => [
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => $perPage,
                        'total' => 0,
                    ],
                ],
                'message' => 'تم جلب الطلاب بنجاح',
                'code' => 200,
            ];
        }

        $items = $students->getCollection()->map(function ($student) {
            return CollegeStudentDTO::fromModel($student)->toArray();
        })->values()->all();

        return [
            'data' => [
                'students' => $items,
                'meta' => [
                    'current_page' => $students->currentPage(),
                    'last_page' => $students->lastPage(),
                    'per_page' => $students->perPage(),
                    'total' => $students->total(),
                ],
            ],
            'message' => 'تم جلب الطلاب بنجاح',
            'code' => 200,
        ];
    }
}
