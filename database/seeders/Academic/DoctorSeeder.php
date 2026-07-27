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
            // ========== Dean ==========
            [
                'person_name' => 'نور الدين أحمد', // person_id = 1
                'department_id' => 1, // هندسة البرمجيات ونظم المعلومات
                'title' => 'دكتور',
                'hire_date' => '2005-09-01',
                'employment_status' => 'active',
            ],
            [
                'person_name' => 'غسان نبيل الحافظ', // person_id = 19
                'department_id' => 2, // الذكاء الاصطناعي
                'title' => 'دكتور',
                'hire_date' => '2003-02-15',
                'employment_status' => 'active',
            ],
            [
                'person_name' => 'نانسي رائف صالح', // person_id = 20
                'department_id' => 3, // النظم والشبكات الحاسوبية
                'title' => 'دكتور',
                'hire_date' => '2010-11-20',
                'employment_status' => 'active',
            ],

            // ========== HeadOfDepartment ==========
            [
                'person_name' => 'هود محمد', // person_id = 2
                'department_id' => 4, // العلوم الأساسية
                'title' => 'دكتور',
                'hire_date' => '2008-06-10',
                'employment_status' => 'active',
            ],

            // ========== Instructor ==========
            [
                'person_name' => 'محمد نور الدين', // person_id = 5
                'department_id' => 1, // هندسة البرمجيات ونظم المعلومات
                'title' => 'دكتور',
                'hire_date' => '2015-03-01',
                'employment_status' => 'active',
            ],
            [
                'person_name' => 'سامر فؤاد العبد', // person_id = 13
                'department_id' => 2, // الذكاء الاصطناعي
                'title' => 'دكتور',
                'hire_date' => '2017-09-15',
                'employment_status' => 'active',
            ],
            [
                'person_name' => 'دعاء إبراهيم الشيخ', // person_id = 14
                'department_id' => 3, // النظم والشبكات الحاسوبية
                'title' => 'دكتور',
                'hire_date' => '2019-01-20',
                'employment_status' => 'active',
            ],
            [
                'person_name' => 'حسام تيسير الحلبي', // person_id = 15
                'department_id' => 4, // العلوم الأساسية
                'title' => 'دكتور',
                'hire_date' => '2014-12-05',
                'employment_status' => 'active',
            ],
            [
                'person_name' => 'خالد يوسف', // person_id = 23
                'department_id' => 1, // هندسة البرمجيات ونظم المعلومات
                'title' => 'دكتور',
                'hire_date' => '2020-08-25',
                'employment_status' => 'active',
            ],
        ];

        foreach ($doctorsData as $data) {
            $person = Person::where('full_name', $data['person_name'])->first();
            if (!$person) {
                continue;
            }
            $exists = Doctor::where('person_id', $person->id)->exists();
            if (!$exists) {
                Doctor::create([
                    'doctor_id_number' => 'DOC-' . $person->id . '-' . rand(1000, 9999),
                    'department_id' => $data['department_id'],
                    'title' => $data['title'],
                    'hire_date' => $data['hire_date'],
                    'employment_status' =>  $data['employment_status'],
                    'person_id' => $person->id,
                ]);
            }
        }
    }
}
