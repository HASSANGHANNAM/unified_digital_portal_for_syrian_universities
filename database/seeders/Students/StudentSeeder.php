<?php

namespace Database\Seeders\Students;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Models\Department;
use App\Models\Person;

class StudentSeeder extends Seeder
{
    public function __construct(
        private StudentRepositoryInterface $studentRepo,
    ) {}

    public function run(): void
    {

        $departments = Department::whereIn('name', [
            'هندسة البرمجيات',
            'الذكاء الاصطناعي',
            'الشبكات',
            'الأمن السيبراني',
            'الجراحة العامة',
            'طب الأطفال',
            'طب النساء والتوليد',
            'الفيزياء',
            'الكيمياء',
            'الأحياء',
        ])->get()->keyBy('name');

        $students = [
            [
                'person_name' => 'عمر خالد',
                'student_id_number' => '20210001',
                'academic_status' => 'مستمر',
                'major' => 'هندسة البرمجيات',
                'enrollment_year' => 2021,
                'current_gpa' => 3.20,
            ],
            [
                'person_name' => 'سارة علي',
                'student_id_number' => '20210002',
                'academic_status' => 'مستمر',
                'major' => 'هندسة البرمجيات',
                'enrollment_year' => 2021,
                'current_gpa' => 3.80,
            ],
            [
                'person_name' => 'محمد ياسين',
                'student_id_number' => '20210003',
                'academic_status' => 'إنذار',
                'major' => 'هندسة البرمجيات',
                'enrollment_year' => 2020,
                'current_gpa' => 1.90,
            ],
            [
                'person_name' => 'ليلى حسن',
                'student_id_number' => '20210004',
                'academic_status' => 'مستمر',
                'major' => 'الذكاء الاصطناعي',
                'enrollment_year' => 2021,
                'current_gpa' => 3.50,
            ],
            [
                'person_name' => 'علي محمود',
                'student_id_number' => '20210005',
                'academic_status' => 'مستمر',
                'major' => 'الشبكات',
                'enrollment_year' => 2021,
                'current_gpa' => 3.10,
            ],
            [
                'person_name' => 'نور الدين أحمد',
                'student_id_number' => '20210006',
                'academic_status' => 'مستمر',
                'major' => 'الأمن السيبراني',
                'enrollment_year' => 2021,
                'current_gpa' => 3.40,
            ],
            [
                'person_name' => 'فاطمة الزهراء يوسف',
                'student_id_number' => '20210007',
                'academic_status' => 'مستمر',
                'major' => 'الجراحة العامة',
                'enrollment_year' => 2021,
                'current_gpa' => 3.60,
            ],
            [
                'person_name' => 'عمر حسن',
                'student_id_number' => '20210008',
                'academic_status' => 'إنذار',
                'major' => 'طب الأطفال',
                'enrollment_year' => 2021,
                'current_gpa' => 3.30,
            ],
            [
                'person_name' => 'سارة عبد الله',
                'student_id_number' => '20210009',
                'academic_status' => 'مستمر',
                'major' => 'طب النساء والتوليد',
                'enrollment_year' => 2021,
                'current_gpa' => 3.70,
            ],
            [
                'person_name' => 'علياء محمد',
                'student_id_number' => '20210010',
                'academic_status' => 'مفصول',
                'major' => 'الفيزياء',
                'enrollment_year' => 2021,
                'current_gpa' => 3.20,
            ],
            [
                'person_name' => 'يوسف علي',
                'student_id_number' => '20210011',
                'academic_status' => 'مستمر',
                'major' => 'الكيمياء',
                'enrollment_year' => 2021,
                'current_gpa' => 3.50,
            ],
            [
                'person_name' => 'ليلى أحمد',
                'student_id_number' => '20210012',
                'academic_status' => 'إنذار',
                'major' => 'الأحياء',
                'enrollment_year' => 2021,
                'current_gpa' => 3.80,
            ],
        ];

        foreach ($students as $studentData) {
            DB::transaction(function () use ($studentData, $departments) {
              
                $person = Person::where('full_name', $studentData['person_name'])->first();

                $departmentName = $studentData['major'];
                $department = $departments[$departmentName] ?? null;

                if (!$department) {
                    throw new \Exception("القسم '{$departmentName}' غير موجود في قاعدة البيانات");
                }

                unset($studentData['person_name']);

                $studentData['person_id'] = $person->id;
                $studentData['college_id'] = $department->college_id;
                $studentData['department_id'] = $department->id;

                $this->studentRepo->create($studentData);
            });
        }
    }
}
