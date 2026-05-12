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

    }
}
