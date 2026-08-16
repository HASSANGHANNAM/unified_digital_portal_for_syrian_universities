<?php

namespace App\Services;

use App\DTOs\DoctorCourseDTO;
use App\DTOs\DoctorListDTO;
use App\DTOs\DoctorUniversityDTO;
use App\Models\Course;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DoctorService
{
    public function __construct(private DoctorRepositoryInterface $doctorRepository) {}

    public function getUniversities(array $validated): array
    {
        $personId = Auth::id() ? Auth::user()?->person_id : null;

        if (!$personId) {
            return [
                'data' => [],
                'message' => 'لم يتم العثور على بيانات الطبيب',
                'code' => 401,
            ];
        }

        $universities = $this->doctorRepository->getUniversitiesByPersonId((int) $personId);

        return [
            'data' => DoctorUniversityDTO::fromCollection($universities),
            'message' => 'تم جلب الجامعات بنجاح',
            'code' => 200,
        ];
    }

    public function getCourses(array $validated): array
    {
        $personId = Auth::user()?->person_id;
        if (!$personId) {
            return [
                'data'    => [],
                'message' => 'لم يتم العثور على بيانات الطبيب',
                'code'    => 401,
            ];
        }

        $perPage = $validated['per_page'] ?? 15;
        $page    = $validated['page'] ?? null;

        $filters = [
            'department_id' => $validated['department_id'] ?? null,
            'course_name'   => $validated['course_name'] ?? null,
        ];

        $paginator = $this->doctorRepository->getCoursesByPersonIdAndCollege(
            (int) $personId,
            (int) $validated['college_id'],
            $filters,
            (int) $perPage,
            $page ? (int) $page : null
        );

        $items = collect($paginator->items())
            ->map(function (Course $course) {
                return DoctorCourseDTO::fromArray([
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
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                ],
            ],
            'message' => 'تم جلب المواد بنجاح',
            'code'    => 200,
        ];
    }
    public function getDoctors(array $filters, int $perPage = 15): array
    {
        $doctors = $this->doctorRepository->getDoctors($filters, $perPage);
        $data = collect($doctors->items())
            ->map(fn($doctor) => DoctorListDTO::fromModel($doctor)->toArray())
            ->values()
            ->toArray();
        return [
            'data' => [
                'Doctors' => $data,
                'meta' => [
                    'current_page' => $doctors->currentPage(),
                    'per_page' => $doctors->perPage(),
                    'total' => $doctors->total(),
                    'last_page' => $doctors->lastPage(),
                ],
            ],
            'message' => 'قائمة الدكاترة.',
            'code' => 200,
        ];
    }
}
