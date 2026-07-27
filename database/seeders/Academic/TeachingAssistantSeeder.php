<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\TeachingAssistant;

class TeachingAssistantSeeder extends Seeder
{
    public function run(): void
    {
        $tasData = [
            // ============================================================
            // المعيد 1: سارة حسن (person_id = 6) - قسم هندسة البرمجيات
            // المشرف: الموظف رقم 1 (نورا علي حسين - affairs.khaled)
            // ============================================================
            [
                'ta_id_number'    => 'TA-6-1001',
                'department_id'   => 1,   // هندسة البرمجيات ونظم المعلومات
                'supervisor_id'   => 1,   // 👈 staff.id = 1 (نورا علي حسين)
                'assignment_date' => '2022-09-01',
                'person_id'       => 6,   // سارة حسن
            ],

            // ============================================================
            // المعيد 2: رنا باسم العقاد (person_id = 16) - قسم الذكاء الاصطناعي
            // المشرف: الموظف رقم 2 (يوسف سامر الحموي - exam.omar)
            // ============================================================
            [
                'ta_id_number'    => 'TA-16-1002',
                'department_id'   => 2,   // الذكاء الاصطناعي
                'supervisor_id'   => 2,   // 👈 staff.id = 2 (يوسف سامر الحموي)
                'assignment_date' => '2021-03-15',
                'person_id'       => 16,  // رنا باسم العقاد
            ],

            // ============================================================
            // المعيد 3: عمار حسام الخطيب (person_id = 17) - قسم النظم والشبكات
            // المشرف: الموظف رقم 3 (هبة الله مصطفى - hiba.mustafa)
            // ============================================================
            [
                'ta_id_number'    => 'TA-17-1003',
                'department_id'   => 3,   // النظم والشبكات الحاسوبية
                'supervisor_id'   => 3,   // 👈 staff.id = 3 (هبة الله مصطفى)
                'assignment_date' => '2023-01-20',
                'person_id'       => 17,  // عمار حسام الخطيب
            ],

            // ============================================================
            // المعيد 4: ريم جورج الخوري (person_id = 26) - قسم النظم والشبكات
            // المشرف: الموظف رقم 3 (هبة الله مصطفى - hiba.mustafa)
            // ============================================================
            [
                'ta_id_number'    => 'TA-26-1004',
                'department_id'   => 3,   // النظم والشبكات الحاسوبية
                'supervisor_id'   => 3,   // 👈 staff.id = 3 (هبة الله مصطفى)
                'assignment_date' => '2022-06-01',
                'person_id'       => 26,  // ريم جورج الخوري
            ],
        ];

        foreach ($tasData as $data) {
            $exists = TeachingAssistant::where('person_id',  $data['person_id'])->exists();
            if (!$exists) {
                TeachingAssistant::create([
                    'ta_id_number' =>  $data['ta_id_number'],
                    'department_id' =>  $data['department_id'],
                    'supervisor_id' => $data['supervisor_id'],
                    'assignment_date' => $data['assignment_date'],
                    'person_id' => $data['person_id'],
                ]);
            }
        }
    }
}
