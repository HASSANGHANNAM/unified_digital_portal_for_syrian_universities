<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use App\Models\College;
use App\Models\Doctor;
use App\Models\CollegeDean;

class CollegeDeanSeeder extends Seeder
{
    public function run(): void
    {
        $deansData = [
            [
                'college_name' => 'كلية الهندسة المعلوماتية',
                'doctor_name' => 'سلمى عبد الرحمن',
                'hired_date' => '2022-09-01',
                'expire_date' => '2026-09-01',
            ],
            [
                'college_name' => 'كلية العلوم',
                'doctor_name' => 'خالد وليد السيد',
                'hired_date' => '2020-09-01',
                'expire_date' => '2024-09-01',
            ],
            [
                'college_name' => 'كلية الآداب والعلوم الإنسانية',
                'doctor_name' => 'غسان نبيل الحافظ',
                'hired_date' => '2023-09-01',
                'expire_date' => '2027-09-01',
            ],
        ];

        foreach ($deansData as $data) {
            $college = College::where('name', $data['college_name'])->first();
            if (!$college) continue;

            $doctor = Doctor::whereHas('person', fn($q) => $q->where('full_name', $data['doctor_name']))->first();
            if (!$doctor) continue;

            $exists = CollegeDean::where('college_id', $college->id)
                ->where('doctor_id', $doctor->id)
                ->exists();

            if (!$exists) {
                CollegeDean::create([
                    'college_id' => $college->id,
                    'doctor_id' => $doctor->id,
                    'hired_date' => $data['hired_date'],
                    'expire_date' => $data['expire_date'],
                ]);
            }
        }
    }
}
