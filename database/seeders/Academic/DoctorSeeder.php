<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use App\Models\Person;
use App\Models\Department;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctorsData = [
            [
                'person_name' => 'محمد نور الدين',
                'department_name' => 'هندسة البرمجيات',
                'assignment_date' => '2015-02-10',
            ],
            [
                'person_name' => 'سلمى عبد الرحمن',
                'department_name' => 'الذكاء الاصطناعي',
                'assignment_date' => '2016-09-15',
            ],
            [
                'person_name' => 'خالد وليد السيد',
                'department_name' => 'فيزياء',
                'assignment_date' => '2010-10-01',
            ],
            [
                'person_name' => 'حسام تيسير الحلبي',
                'department_name' => 'أمراض باطنية',
                'assignment_date' => '2011-09-01',
            ],
            [
                'person_name' => 'غسان نبيل الحافظ',
                'department_name' => 'لغة عربية',
                'assignment_date' => '2005-09-01',
            ],
        ];

        foreach ($doctorsData as $data) {
            $person = Person::where('full_name', $data['person_name'])->first();
            if (!$person) {
                continue;
            }

            $department = Department::where('name', $data['department_name'])->first();
            if (!$department) {
                continue;
            }

            $exists = Doctor::where('person_id', $person->id)->exists();
            if (!$exists) {
                Doctor::create([
                    'doctor_id_number' => 'DOC-' . $person->id . '-' . rand(1000, 9999),
                    'department_id' => $department->id,
                    'title' => 'دكتور',
                    'hire_date' => $data['assignment_date'],
                    'employment_status' => 'active',
                    'person_id' => $person->id,
                ]);
            }
        }
    }
}
