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
                'department_name' => 'هندسة البرمجيات',
                'doctor_name' => 'محمد نور الدين',
                'hired_date' => '2020-09-01',
                'expire_date' => '2025-09-01',
            ],
            [
                'department_name' => 'الذكاء الاصطناعي',
                'doctor_name' => 'سلمى عبد الرحمن',
                'hired_date' => '2021-09-01',
                'expire_date' => '2026-09-01',
            ],
            [
                'department_name' => 'فيزياء',
                'doctor_name' => 'خالد وليد السيد',
                'hired_date' => '2019-09-01',
                'expire_date' => '2024-09-01',
            ],
        ];

        foreach ($headsData as $data) {
            $department = Department::where('name', $data['department_name'])->first();
            if (!$department) continue;

            $doctor = Doctor::whereHas('person', fn($q) => $q->where('full_name', $data['doctor_name']))->first();
            if (!$doctor) continue;

            $exists = DepartmentHead::where('department_id', $department->id)
                ->where('doctor_id', $doctor->id)
                ->exists();

            if (!$exists) {
                DepartmentHead::create([
                    'department_id' => $department->id,
                    'doctor_id' => $doctor->id, // تأكد من اسم العمود في جدول department_heads
                    'hired_date' => $data['hired_date'],
                    'expire_date' => $data['expire_date'],
                ]);
            }
        }
    }
}
