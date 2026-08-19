<?php

namespace App\Services;

use App\DTOs\StaffListDTO;
use App\Models\Department;
use App\Models\Person;
use App\Models\User;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\StaffRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffService
{
    public function __construct(
        private StaffRepositoryInterface $staffRepositoryInterface,
        private UserRepositoryInterface $userRepository,
        private PersonRepositoryInterface $personRepository,
    ) {}
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

        $staffIdNumber = 'STF-' . Carbon::now()->format('Ymd') . '-' . Str::random(6);
        $data = [
            'staff_id_number'   => $staffIdNumber,
            'department_id'     => $validated['department_id'],
            'person_id'         => $validated['person_id'],
            'hire_date'         => Carbon::now()->toDateString(),
            'employment_status' => 'active',
        ];
        $user = User::where('persone_id', $validated['person_id']);
        $user->assignRole($validated['role']);
        $staff = $this->staffRepositoryInterface->create($data);

        return [
            'data'    => $staff,
            'message' => 'تم إضافة الموظف بنجاح.',
            'code'    => 201,
        ];
    }
    public function addStaff(array $validated): array
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
            $staff = $this->staffRepositoryInterface->create([
                'person_id' => $person->id,
                'department_id' => null,
                'staff_id_number' => 'STF-' . Carbon::now()->format('Ymd') . '-' . Str::random(6),
                'hire_date' => Carbon::now()->toDateString(),
                'employment_status' => 'inactive',
            ]);
            return [
                'data' => [
                    'user' => $user,
                    'person' => $person,
                    'staff' => $staff,
                ],
                'message' => 'تم إضافة الموظف بنجاح.',
                'code' => 201,
            ];
        });
    }
}
