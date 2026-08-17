<?php

namespace App\Services;

use App\DTOs\DoctorCourseDTO;
use App\DTOs\DoctorListDTO;
use App\DTOs\DoctorUniversityDTO;
use App\Models\Course;
use App\Models\Department;
use App\Models\Person;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DoctorService
{
    public function __construct(
        private DoctorRepositoryInterface $doctorRepository,
        private UserRepositoryInterface $userRepository,
        private PersonRepositoryInterface $personRepository,
    ) {}

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

        $person = Person::find($validated['person_id']);
        if (!$person) {
            return [
                'data' => [],
                'message' => 'الشخص غير موجود.',
                'code' => 404,
            ];
        }
        $doctorIdNumber = 'DOC-' . Carbon::now()->format('Ymd') . '-' . Str::random(6);
        $data = [
            'doctor_id_number'   => $doctorIdNumber,
            'department_id'      => $validated['department_id'],
            'title'              => $validated['title'] ?? null,
            'hire_date'          => Carbon::now()->toDateString(),
            'employment_status'  => 'active',
            'person_id'          => $validated['person_id'],
        ];
        $doctor = $this->doctorRepository->create($data);

        return [
            'data'    => $doctor,
            'message' => 'تم إضافة الدكتور بنجاح.',
            'code'    => 201,
        ];
    }
    public function addDoctors(array $validated): array
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
            $user->assignRole('Instructor');
            $doctor = $this->doctorRepository->create([
                'person_id' => $person->id,
                'department_id' => null,
                'doctor_id_number' => 'DOC-' . Carbon::now()->format('Ymd') . '-' . Str::random(6),
                'title' => 'دكتور',
                'hire_date' => Carbon::now()->toDateString(),
                'employment_status' => 'inactive',
            ]);

            return [
                'data' => [
                    'user' => $user,
                    'person' => $person,
                    'doctor' => $doctor,
                ],
                'message' => 'تم إضافة الدكتور بنجاح.',
                'code' => 201,
            ];
        });
    }
}
