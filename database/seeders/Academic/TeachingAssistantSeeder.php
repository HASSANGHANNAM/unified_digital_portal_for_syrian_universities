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
            [
                'person_name' => 'رنا باسم العقاد',
                'department_name' => 'هندسة البرمجيات',
                'supervisor_name' => 'محمد نور الدين',
                'assignment_date' => '2018-03-01',
            ],
            [
                'person_name' => 'عمار حسام الخطيب',
                'department_name' => 'الذكاء الاصطناعي',
                'supervisor_name' => 'سلمى عبد الرحمن',
                'assignment_date' => '2017-01-10',
            ],
            [
                'person_name' => 'ريم جورج الخوري',
                'department_name' => 'رياضيات',
                'supervisor_name' => 'خالد وليد السيد',
                'assignment_date' => '2016-09-01',
            ],
        ];

        foreach ($tasData as $data) {
            $person = Person::where('full_name', $data['person_name'])->first();
            if (!$person) continue;

            $department = Department::where('name', $data['department_name'])->first();
            if (!$department) continue;

            $supervisor = Doctor::whereHas('person', fn($q) => $q->where('full_name', $data['supervisor_name']))->first();
            if (!$supervisor) continue;

            $exists = TeachingAssistant::where('person_id', $person->id)->exists();
            if (!$exists) {
                TeachingAssistant::create([
                    'ta_id_number' => 'TA-' . $person->id . '-' . rand(1000, 9999),
                    'department_id' => $department->id,
                    'supervisor_id' => $supervisor->id,
                    'assignment_date' => $data['assignment_date'],
                    'person_id' => $person->id,
                ]);
            }
        }
    }
}
