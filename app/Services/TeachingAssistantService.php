<?php

namespace App\Services;

use App\DTOs\DoctorCourseDTO;
use App\DTOs\DoctorUniversityDTO;
use App\DTOs\TaListDTO;
use App\DTOs\TeachingAssistantCourseDTO;
use App\DTOs\TeachingAssistantUniversityDTO;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Person;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\TeachingAssistantRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeachingAssistantService
{
    public function __construct(
        private TeachingAssistantRepositoryInterface $teachingAssistantRepository,
        private UserRepositoryInterface $userRepository,
        private PersonRepositoryInterface $personRepository,
    ) {}

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
    public function getTeachingAssistants(array $filters, int $perPage = 15): array
    {
        $tas = $this->teachingAssistantRepository->getTeachingAssistants($filters, $perPage);

        $data = collect($tas->items())
            ->map(fn($ta) => TaListDTO::fromModel($ta)->toArray())
            ->values()
            ->toArray();

        return [
            'data' => [
                'teaching-assistants' => $data,
                'meta' => [
                    'current_page' => $tas->currentPage(),
                    'per_page' => $tas->perPage(),
                    'total' => $tas->total(),
                    'last_page' => $tas->lastPage(),
                ],
            ],
            'message' => 'قائمة المعيدين.',
            'code' => 200,
        ];
    }
    public function store(array $validated): array
    {
        $department = Department::find($validated['department_id']);
        if (!$department) {
            return [
                'data' => [],
                'message' => 'القسم غير موجود.',
                'code' => 404,
            ];
        }

        $supervisor = Doctor::find($validated['supervisor_id']);
        if (!$supervisor) {
            return [
                'data' => [],
                'message' => 'المشرف غير موجود.',
                'code' => 404,
            ];
        }
        $person = Person::find($validated['person_id']);
        if (!$person) {
            return [
                'data' => [],
                'message' => 'الشخص غير موجود.',
                'code' => 404,
            ];
        }

        $taIdNumber = 'TA-' . Carbon::now()->format('Ymd') . '-' . Str::random(6);
        $data = [
            'ta_id_number'    => $taIdNumber,
            'department_id'   => $validated['department_id'],
            'supervisor_id'   => $validated['supervisor_id'],
            'person_id'       => $validated['person_id'],
            'assignment_date' => Carbon::now()->toDateString(),
        ];

        $ta = $this->teachingAssistantRepository->create($data);

        return [
            'data'    => $ta,
            'message' => 'تم إضافة المعيد بنجاح.',
            'code'    => 201,
        ];
    }
    public function addTeachingAssistants(array $validated): array
    {
        return DB::transaction(function () use ($validated) {
            $person = $this->personRepository->create([
                'national_id' => $validated['national_id'],
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'national_number' => $validated['national_number'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            $user = $this->userRepository->create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'person_id' => $person->id,
                'password' => Hash::make($validated['password']),
                'status' => 'active',
            ]);

            $user->assignRole('TeachingAssistant');

            $ta = $this->teachingAssistantRepository->create([
                'person_id' => $person->id,
                'department_id' => null,
                'ta_id_number' => 'TA-' . Carbon::now()->format('Ymd') . '-' . Str::random(6),
                'supervisor_id' => null,
                'assignment_date' => Carbon::now()->toDateString(),
            ]);

            return [
                'data' => [
                    'user' => $user,
                    'person' => $person,
                    'teaching_assistant' => $ta,
                ],
                'message' => 'تم إضافة المعيد بنجاح.',
                'code' => 201,
            ];
        });
    }
}
