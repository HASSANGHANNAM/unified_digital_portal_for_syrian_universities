<?php

namespace App\Services;

use App\DTOs\CourseDetailsDTO;
use App\Repositories\Contracts\CourseRepositoryInterface;

class CourseService
{
    public function __construct(private CourseRepositoryInterface $courseRepositoryInterface) {}

    public function getCourseDetails(array $validated): array
    {
        $courseId = (int) ($validated['course_id'] ?? 0);
        $course = $this->courseRepositoryInterface->getCourseWithParts($courseId, 15);

        if (!$course) {
            return [
                'data' => [
                    'data' => [],
                    'meta' => [
                        'current_page' => 1,
                        'last_page' => 1,
                        'per_page' => 15,
                        'total' => 0,
                    ],
                ],
                'message' => 'تم جلب تفاصيل المقرر بنجاح',
                'code' => 200,
            ];
        }

        $item = CourseDetailsDTO::fromModel($course)->toArray();

        return [
            'data' => [
                'data' => [$item],
                'meta' => [
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 15,
                    'total' => 1,
                ],
            ],
            'message' => 'تم جلب تفاصيل المقرر بنجاح',
            'code' => 200,
        ];
    }
}
