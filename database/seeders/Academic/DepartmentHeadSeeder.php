<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\DepartmentHead;

class DepartmentHeadSeeder extends Seeder
{
    public function run(): void
    {
        $headsData = [

            [
                'department_id' => 1,  // id الخاص بقسم "هندسة البرمجيات ونظم المعلومات" في كلية الهندسة بجامعة دمشق
                'doctor_name'   => 'هود محمد',
                'hired_date'    => '2020-09-01',
                'expire_date'   => '2025-09-01',
            ],
        ];

        foreach ($headsData as $data) {
            $doctor = Doctor::whereHas('person', fn($q) => $q->where('full_name', $data['doctor_name']))->first();
            if (!$doctor) continue;

            $exists = DepartmentHead::where('department_id', $data['department_id'])
                ->where('doctor_id', $doctor->id)
                ->exists();

            if (!$exists) {
                DepartmentHead::create([
                    'department_id' => $data['department_id'],
                    'doctor_id' => $doctor->id,
                    'hired_date' => $data['hired_date'],
                    'expire_date' => $data['expire_date'],
                ]);
            }
        }
    }
}
