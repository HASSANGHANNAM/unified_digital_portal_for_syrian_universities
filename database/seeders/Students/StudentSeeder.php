<?php

namespace Database\Seeders\Students;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Models\Department;
use App\Models\Person;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function __construct(
        private StudentRepositoryInterface $studentRepo,
    ) {}

    public function run(): void
    {
        // جلب جميع الأقسام
        $departments = Department::all()->keyBy('name');

        $students = [
            // -------------------- الطلاب القدامى --------------------
            ['person_name' => 'عمر خالد', 'student_id_number' => '20210001', 'academic_status' => 'مستمر', 'major' => 'هندسة البرمجيات', 'enrollment_year' => 2021, 'current_gpa' => 3.20],
            ['person_name' => 'سارة علي', 'student_id_number' => '20210002', 'academic_status' => 'مستمر', 'major' => 'هندسة البرمجيات', 'enrollment_year' => 2021, 'current_gpa' => 3.80],
            ['person_name' => 'محمد ياسين', 'student_id_number' => '20210003', 'academic_status' => 'إنذار', 'major' => 'هندسة البرمجيات', 'enrollment_year' => 2020, 'current_gpa' => 1.90],
            ['person_name' => 'ليلى حسن', 'student_id_number' => '20210004', 'academic_status' => 'مستمر', 'major' => 'الذكاء الاصطناعي', 'enrollment_year' => 2021, 'current_gpa' => 3.50],
            ['person_name' => 'علي محمود', 'student_id_number' => '20210005', 'academic_status' => 'مستمر', 'major' => 'الشبكات', 'enrollment_year' => 2021, 'current_gpa' => 3.10],
            ['person_name' => 'نور الدين أحمد', 'student_id_number' => '20210006', 'academic_status' => 'مستمر', 'major' => 'الأمن السيبراني', 'enrollment_year' => 2021, 'current_gpa' => 3.40],
            ['person_name' => 'فاطمة الزهراء يوسف', 'student_id_number' => '20210007', 'academic_status' => 'مستمر', 'major' => 'الجراحة العامة', 'enrollment_year' => 2021, 'current_gpa' => 3.60],
            ['person_name' => 'عمر حسن', 'student_id_number' => '20210008', 'academic_status' => 'إنذار', 'major' => 'طب الأطفال', 'enrollment_year' => 2021, 'current_gpa' => 3.30],
            ['person_name' => 'سارة عبد الله', 'student_id_number' => '20210009', 'academic_status' => 'مستمر', 'major' => 'طب النساء والتوليد', 'enrollment_year' => 2021, 'current_gpa' => 3.70],
            ['person_name' => 'علياء محمد', 'student_id_number' => '20210010', 'academic_status' => 'مفصول', 'major' => 'فيزياء', 'enrollment_year' => 2021, 'current_gpa' => 3.20],
            ['person_name' => 'يوسف علي', 'student_id_number' => '20210011', 'academic_status' => 'مستمر', 'major' => 'الكيمياء', 'enrollment_year' => 2021, 'current_gpa' => 3.50],
            ['person_name' => 'ليلى أحمد', 'student_id_number' => '20210012', 'academic_status' => 'إنذار', 'major' => 'الأحياء', 'enrollment_year' => 2021, 'current_gpa' => 3.80],

            // -------------------- الطلاب الجدد (مع تصحيح الأرقام المكررة) --------------------
            ['person_name' => 'أحمد محمد العلي', 'student_id_number' => '20220001', 'academic_status' => 'مستمر', 'major' => 'هندسة البرمجيات', 'enrollment_year' => 2022, 'current_gpa' => 3.2],
            ['person_name' => 'فاطمة خالد الحسين', 'student_id_number' => '20220002', 'academic_status' => 'مستمر', 'major' => 'الذكاء الاصطناعي', 'enrollment_year' => 2022, 'current_gpa' => 3.5],
            ['person_name' => 'يوسف سامر الحموي', 'student_id_number' => '20220003', 'academic_status' => 'مستمر', 'major' => 'رياضيات', 'enrollment_year' => 2022, 'current_gpa' => 2.9],
            ['person_name' => 'نورا علي حسين', 'student_id_number' => '20210014', 'academic_status' => 'مستمر', 'major' => 'فيزياء', 'enrollment_year' => 2021, 'current_gpa' => 3.7],  // تم تغيير الرقم المكرر
            ['person_name' => 'لينا جمال عزام', 'student_id_number' => '20230005', 'academic_status' => 'مستمر', 'major' => 'طب بشري', 'enrollment_year' => 2023, 'current_gpa' => 3.9],
            ['person_name' => 'رامي عدنان الخطيب', 'student_id_number' => '20230006', 'academic_status' => 'مستمر', 'major' => 'إدارة أعمال', 'enrollment_year' => 2023, 'current_gpa' => 3.1],
            ['person_name' => 'دعاء إبراهيم الشيخ', 'student_id_number' => '20200007', 'academic_status' => 'متخرج', 'major' => 'لغة عربية', 'enrollment_year' => 2020, 'current_gpa' => 3.3],
            ['person_name' => 'ميساء أنور حمود', 'student_id_number' => '20240008', 'academic_status' => 'مستمر', 'major' => 'محاسبة', 'enrollment_year' => 2024, 'current_gpa' => 2.8],
            ['person_name' => 'باسل أكرم النوري', 'student_id_number' => '20240009', 'academic_status' => 'مستمر', 'major' => 'تاريخ', 'enrollment_year' => 2024, 'current_gpa' => 3.6],
        ];

        foreach ($students as $studentData) {
            DB::transaction(function () use ($studentData, $departments) {
                $person = Person::where('full_name', $studentData['person_name'])->first();
                if (!$person) {
                    throw new \Exception("الشخص '{$studentData['person_name']}' غير موجود");
                }

                $department = $departments[$studentData['major']] ?? null;
                if (!$department) {
                    throw new \Exception("القسم '{$studentData['major']}' غير موجود");
                }

                // تجنب التكرار: استخدم firstOrCreate بدلاً من create
                $existing = Student::where('student_id_number', $studentData['student_id_number'])->first();
                if (!$existing) {
                    $this->studentRepo->create([
                        'student_id_number' => $studentData['student_id_number'],
                        'academic_status' => $studentData['academic_status'],
                        'major' => $studentData['major'],
                        'enrollment_year' => $studentData['enrollment_year'],
                        'current_gpa' => $studentData['current_gpa'],
                        'person_id' => $person->id,
                        'college_id' => $department->college_id,
                        'department_id' => $department->id,
                    ]);
                } else {
                }
            });
        }
    }
}
