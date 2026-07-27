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
            // 1. نور الدين أحمد - عميد كلية الهندسة المعلوماتية (college_id = 1)
            [
                'college_id'   => 1,  // كلية الهندسة المعلوماتية - جامعة دمشق
                'doctor_id'    => 1,  // person_id لنور الدين أحمد
                'hired_date'   => '2020-09-01',
                'expire_date'  => '2025-09-01',
            ],
            // 2. غسان نبيل الحافظ - عميد كلية الطب البشري (college_id = 2)
            [
                'college_id'   => 2,  // كلية الطب البشري - جامعة دمشق
                'doctor_id'    => 2, // person_id لغسان نبيل الحافظ
                'hired_date'   => '2018-03-15',
                'expire_date'  => '2024-03-15',
            ],
            // 3. نانسي رائف صالح - عميدة كلية الآداب (college_id = 5)
            [
                'college_id'   => 5,  // كلية الآداب - جامعة دمشق
                'doctor_id'    => 3, // person_id لنانسي رائف صالح
                'hired_date'   => '2021-11-01',
                'expire_date'  => '2026-11-01',
            ],
        ];

        foreach ($deansData as $data) {

            $exists = CollegeDean::where('college_id', $data['college_id'])
                ->where('doctor_id', $data['doctor_id'])
                ->exists();

            if (!$exists) {
                CollegeDean::create([
                    'college_id' => $data['college_id'],
                    'doctor_id' => $data['doctor_id'],
                    'hired_date' => $data['hired_date'],
                    'expire_date' => $data['expire_date'],
                ]);
            }
        }
    }
}
