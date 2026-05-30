<?php

namespace Database\Seeders\Sanctions;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\SanctionRepositoryInterface;
use App\Models\Student;
use App\Models\Staff;
use App\Models\SanctionType;
use App\Models\Course;

class SanctionSeeder extends Seeder
{
    public function __construct(
        private SanctionRepositoryInterface $sanctionRepo,
    ) {}

    public function run(): void
    {
        $student = Student::where('student_id_number', '20210003')->first();
        $staff = Staff::first();
        $course = Course::first();

        // عقوبة 1: إنذار امتحاني - ongoing (جارية)
        $sanctionType1 = SanctionType::where('name', 'إنذار امتحاني')->first();
        DB::transaction(function () use ($student, $staff, $course, $sanctionType1) {
            $this->sanctionRepo->create([
                'sanction_type_id' => $sanctionType1->id,
                'status' => 'ongoing',
                'issued_date' => now(),
                'expiry_date' => now()->addDays(30),
                'notes' => 'تم ضبط الطالب أثناء محاولة الغش في الامتحان.',
                'student_response' => 'أتعهد بعدم تكرار المخالفة.',
                'staff_response' => 'تم تسجيل العقوبة أصولاً.',
                'student_id' => $student->id,
                'staff_id' => $staff->id,
                'course_id' => $course->id,
            ]);
        });

        // عقوبة 2: إنذار سلوكي - expired (منتهية)
        $sanctionType2 = SanctionType::where('name', 'إنذار سلوكي')->first();
        DB::transaction(function () use ($student, $staff, $sanctionType2) {
            $this->sanctionRepo->create([
                'sanction_type_id' => $sanctionType2->id,
                'status' => 'expired',
                'issued_date' => now()->subDays(60),
                'expiry_date' => now()->subDays(30),
                'notes' => 'تسبب الطالب في إزعاج الآخرين داخل المحاضرة.',
                'student_response' => 'أعتذر عن سلوكي.',
                'staff_response' => 'تم توجيه تنبيه للطالب.',
                'student_id' => $student->id,
                'staff_id' => $staff->id,
                'course_id' => null,
            ]);
        });

        // عقوبة 3: حرمان من مقرر - مطعون (مطعون عليها)
        $sanctionType3 = SanctionType::where('name', 'حرمان من مقرر')->first();
        DB::transaction(function () use ($student, $staff, $course, $sanctionType3) {
            $this->sanctionRepo->create([
                'sanction_type_id' => $sanctionType3->id,
                'status' => 'مطعون',
                'issued_date' => now()->subDays(15),
                'expiry_date' => now()->addDays(180),
                'notes' => 'الغش أثناء الامتحان النهائي.',
                'student_response' => 'لم أحاول الغش، أطلب إعادة النظر.',
                'staff_response' => 'تم استلام الطعن وجاري دراسته.',
                'student_id' => $student->id,
                'staff_id' => $staff->id,
                'course_id' => $course->id,
            ]);
        });

        // عقوبة 4: إنذار غياب - مناقشة الطعن (قيد المناقشة)
        $sanctionType4 = SanctionType::where('name', 'إنذار غياب')->first();
        DB::transaction(function () use ($student, $staff, $course, $sanctionType4) {
            $this->sanctionRepo->create([
                'sanction_type_id' => $sanctionType4->id,
                'status' => 'مناقشة الطعن',
                'issued_date' => now()->subDays(10),
                'expiry_date' => null,
                'notes' => 'تجاوز الطالب نسبة الغياب المسموح بها.',
                'student_response' => 'كان لدي ظروف صحية تمنعني من الحضور.',
                'staff_response' => 'تم فتح تحقيق في الموضوع.',
                'student_id' => $student->id,
                'staff_id' => $staff->id,
                'course_id' => $course->id,
            ]);
        });

        // ========== البيانات الجديدة من dummyData.ts ==========
        // 1. عقوبة إنذار كتابي للطالب "يوسف سامر الحموي"
        $student1 = Student::whereHas('person', fn($q) => $q->where('full_name', 'يوسف سامر الحموي'))->first();
        $staff1 = Staff::whereHas('person', fn($q) => $q->where('full_name', 'أحمد محمود'))->first();
        $course1 = Course::where('code', 'MATH101')->first();
        $type1 = SanctionType::where('name', 'إنذار كتابي')->first();
        if ($student1 && $staff1 && $course1 && $type1) {
            $this->sanctionRepo->create([
                'sanction_type_id' => $type1->id,
                'status' => 'expired',
                'issued_date' => '2024-10-01',
                'expiry_date' => '2025-01-01',
                'notes' => 'تغيب 3 مرات متتالية',
                'student_response' => null,
                'staff_response' => null,
                'student_id' => $student1->id,
                'staff_id' => $staff1->id,
                'course_id' => $course1->id,
            ]);
        }

        // 2. عقوبة فصل مؤقت للطالب "أحمد محمد العلي"
        $student2 = Student::whereHas('person', fn($q) => $q->where('full_name', 'أحمد محمد العلي'))->first();
        $staff2 = Staff::whereHas('person', fn($q) => $q->where('full_name', 'محمد علي'))->first();
        $course2 = Course::where('code', 'CS201')->first();
        $type2 = SanctionType::where('name', 'فصل مؤقت')->first();
        if ($student2 && $staff2 && $course2 && $type2) {
            $this->sanctionRepo->create([
                'sanction_type_id' => $type2->id,
                'status' => 'ongoing', // تم تغييرها من 'active' إلى 'ongoing'
                'issued_date' => '2025-02-15',
                'expiry_date' => '2025-08-15',
                'notes' => 'ضبط بحالة غش في مادة هياكل البيانات',
                'student_response' => 'أقر بالخطأ',
                'staff_response' => 'تم اتخاذ الإجراء',
                'student_id' => $student2->id,
                'staff_id' => $staff2->id,
                'course_id' => $course2->id,
            ]);
        }

        // 3. عقوبة حرمان من التقدم للامتحانات للطالبة "لينا جمال عزام"
        $student3 = Student::whereHas('person', fn($q) => $q->where('full_name', 'لينا جمال عزام'))->first();
        $staff3 = Staff::whereHas('person', fn($q) => $q->where('full_name', 'سارة حسن'))->first();
        $course3 = Course::where('code', 'MED201')->first();
        $type3 = SanctionType::where('name', 'حرمان من التقدم للامتحانات')->first();
        if ($student3 && $staff3 && $course3 && $type3) {
            $this->sanctionRepo->create([
                'sanction_type_id' => $type3->id,
                'status' => 'مناقشة الطعن',   // تم التغيير من 'appealDiscussion'
                'issued_date' => '2025-03-20',
                'expiry_date' => '2025-06-05',
                'notes' => 'إساءة لفظية تجاه معيد',
                'student_response' => 'قدم التماساً',
                'staff_response' => null,
                'student_id' => $student3->id,
                'staff_id' => $staff3->id,
                'course_id' => $course3->id,
            ]);
        }
    }
}
