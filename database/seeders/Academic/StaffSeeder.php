<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StaffRepositoryInterface;
use App\Models\Department;
use App\Models\Person;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function __construct(
        private StaffRepositoryInterface $staffRepo,
    ) {}

    public function run(): void
    {

        $newStaffData = [
            // ========== StudentAffairs (شؤون الطلاب) ==========
            [
                'person_id'         => 4,   // نورا علي حسين (affairs.khaled)
                'department_id'     => 1,   // قسم هندسة البرمجيات ونظم المعلومات
                'hire_date'         => '2021-09-01',
                'employment_status' => 'active',
                'staff_id_number'   => 'STF-12-001',
            ],

            // ========== Examination (شؤون الامتحانات) ==========
            [
                'person_id'         => 3,   // يوسف سامر الحموي (exam.omar)
                'department_id'     => 2,   // قسم الذكاء الاصطناعي
                'hire_date'         => '2020-03-15',
                'employment_status' => 'active',
                'staff_id_number'   => 'STF-12-002',
            ],
            [
                'person_id'         => 12,  // هبة الله مصطفى (hiba.mustafa)
                'department_id'     => 3,   // قسم النظم والشبكات الحاسوبية
                'hire_date'         => '2022-06-01',
                'employment_status' => 'active',
                'staff_id_number'   => 'STF-12-003',
            ],

        ];

        foreach ($newStaffData as $staffData) {
            $exists = Staff::where('person_id', $staffData['person_id'])->exists();
            if ($exists) {
                continue;
            }
            DB::transaction(function () use ($staffData) {
                $this->staffRepo->create([
                    'staff_id_number' => $staffData['staff_id_number'],
                    'department_id' => $staffData['department_id'],
                    'hire_date' => $staffData['hire_date'],
                    'employment_status' => $staffData['employment_status'],
                    'person_id' => $staffData['person_id'],
                ]);
            });
        }
    }
}
