<?php

namespace App\Services;

use App\DTOs\DoctorCourseDTO;
use App\DTOs\DoctorUniversityDTO;
use App\DTOs\TeachingAssistantCourseDTO;
use App\DTOs\TeachingAssistantUniversityDTO;
use App\Repositories\Contracts\TeachingAssistantRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TeachingAssistantService
{
    public function __construct(private TeachingAssistantRepositoryInterface $teachingAssistantRepository) {}

    public function getUniversities(array $validated): array
    {
        $personId = Auth::user()?->person_id;
        if (!$personId) {
            return [
                'data' => [],
                'message' => 'لم يتم العثور على بيانات المعيد',
                'code' => 401,
            ];
        }

        $universities = $this->teachingAssistantRepository->getUniversitiesByPersonId((int) $personId);

        return [
            'data' => TeachingAssistantUniversityDTO::fromCollection($universities),
            'message' => 'تم جلب الجامعات بنجاح',
            'code' => 200,
        ];
    }

    public function getCourses(array $validated): array
    {
        $personId = Auth::user()?->person_id;
        if (!$personId) {
            return [
                'data' => [],
                'message' => 'لم يتم العثور على بيانات المعيد',
                'code' => 401,
            ];
        }

        $perPage = $validated['per_page'] ?? 15;
        $page = $validated['page'] ?? null;
        $filters = [
            'department_id' => $validated['department_id'] ?? null,
            'course_name' => $validated['course_name'] ?? null,
        ];

        $paginator = $this->teachingAssistantRepository->getCoursesByPersonIdAndCollege(
            (int) $personId,
            (int) $validated['college_id'],
            $filters,
            (int) $perPage,
            $page ? (int) $page : null
        );

        $items = collect($paginator->items())
            ->map(function ($course) {
                return TeachingAssistantCourseDTO::fromArray([
                    'course_id' => $course->id,
                    'course_name' => $course->universalCourse?->name,
                    'course_code' => $course->code,
                    'credits' => $course->credits,
                    'department_id' => $course->department_id,
                    'department_name' => $course->department?->name,
                    'college_id' => $course->college_id,
                    'college_name' => $course->college?->name,
                    'university_id' => $course->college?->university?->id,
                    'university_name' => $course->college?->university?->name,
                ]);
            })
            ->values()
            ->all();

        return [
            'data' => [
                'items' => $items,
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ],
            'message' => 'تم جلب المواد بنجاح',
            'code' => 200,
        ];
    }
}
