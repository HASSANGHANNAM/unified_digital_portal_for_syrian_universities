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
        $studentIds = ['20210008', '20210009', '20210010', '20210011', '20210012'];
        $students = Student::whereIn('student_id_number', $studentIds)->get()->keyBy('student_id_number');
        $defaultStudent = Student::first();
        $staff = Staff::first();
        $course = Course::first();

        // 1. عقوبة: إنذار امتحاني - جارية (الطالب 20210008)
        $student8 = $students->get('20210008') ?? $defaultStudent;
        $sanctionType1 = SanctionType::where('name', 'إنذار امتحاني')->first();
        if ($sanctionType1 && $student8) {
            DB::transaction(function () use ($student8, $staff, $course, $sanctionType1) {
                $this->sanctionRepo->create([
                    'sanction_type_id' => $sanctionType1->id,
                    'status' => 'ongoing',
                    'issued_date' => now(),
                    'expiry_date' => now()->addDays(30),
                    'notes' => 'تم ضبط الطالب أثناء محاولة الغش في الامتحان.',
                    'student_response' => 'أتعهد بعدم تكرار المخالفة.',
                    'staff_response' => 'تم تسجيل العقوبة أصولاً.',
                    'student_id' => $student8->id,
                    'staff_id' => $staff->id,
                    'course_id' => $course->id,
                ]);
            });
        }

        // 2. عقوبة: إنذار سلوكي - منتهية (الطالب 20210009)
        $student9 = $students->get('20210009') ?? $defaultStudent;
        $sanctionType2 = SanctionType::where('name', 'إنذار سلوكي')->first();
        if ($sanctionType2 && $student9) {
            DB::transaction(function () use ($student9, $staff, $sanctionType2) {
                $this->sanctionRepo->create([
                    'sanction_type_id' => $sanctionType2->id,
                    'status' => 'expired',
                    'issued_date' => now()->subDays(60),
                    'expiry_date' => now()->subDays(30),
                    'notes' => 'تسبب الطالب في إزعاج الآخرين داخل المحاضرة.',
                    'student_response' => 'أعتذر عن سلوكي.',
                    'staff_response' => 'تم توجيه تنبيه للطالب.',
                    'student_id' => $student9->id,
                    'staff_id' => $staff->id,
                    'course_id' => null,
                ]);
            });
        }

        // 3. عقوبة: حرمان من مقرر - مطعون عليها (الطالب 20210010)
        $student10 = $students->get('20210010') ?? $defaultStudent;
        $sanctionType3 = SanctionType::where('name', 'حرمان من مقرر')->first();
        if ($sanctionType3 && $student10) {
            DB::transaction(function () use ($student10, $staff, $course, $sanctionType3) {
                $this->sanctionRepo->create([
                    'sanction_type_id' => $sanctionType3->id,
                    'status' => 'مطعون',
                    'issued_date' => now()->subDays(15),
                    'expiry_date' => now()->addDays(180),
                    'notes' => 'الغش أثناء الامتحان النهائي.',
                    'student_response' => 'لم أحاول الغش، أطلب إعادة النظر.',
                    'staff_response' => 'تم استلام الطعن وجاري دراسته.',
                    'student_id' => $student10->id,
                    'staff_id' => $staff->id,
                    'course_id' => $course->id,
                ]);
            });
        }

        // 4. عقوبة: إنذار غياب - قيد المناقشة (الطالب 20210011)
        $student11 = $students->get('20210011') ?? $defaultStudent;
        $sanctionType4 = SanctionType::where('name', 'إنذار غياب')->first();
        if ($sanctionType4 && $student11) {
            DB::transaction(function () use ($student11, $staff, $course, $sanctionType4) {
                $this->sanctionRepo->create([
                    'sanction_type_id' => $sanctionType4->id,
                    'status' => 'مناقشة الطعن',
                    'issued_date' => now()->subDays(10),
                    'expiry_date' => null,
                    'notes' => 'تجاوز الطالب نسبة الغياب المسموح بها.',
                    'student_response' => null,
                    'staff_response' => 'تم فتح تحقيق في الموضوع.',
                    'student_id' => $student11->id,
                    'staff_id' => $staff->id,
                    'course_id' => $course->id,
                ]);
            });
        }

        // 5. عقوبة إضافية: حرمان مؤقت من الأنشطة - جارية (الطالب 20210012)
        $student12 = $students->get('20210012') ?? $defaultStudent;
        $sanctionType5 = SanctionType::where('name', 'حرمان مؤقت')->first() ?? $sanctionType2; // فحص أمان في حال عدم وجود النوع
        if ($student12) {
            DB::transaction(function () use ($student12, $staff, $sanctionType5) {
                $this->sanctionRepo->create([
                    'sanction_type_id' => $sanctionType5->id,
                    'status' => 'ongoing',
                    'issued_date' => now()->subDays(2),
                    'expiry_date' => now()->addDays(14),
                    'notes' => 'إلحاق الضرر المتعمد بممتلكات المختبر أو القاعة الدراسية.',
                    'student_response' => 'مستعد لتحمل تكاليف الإصلاح.',
                    'staff_response' => 'تمت إحالة الملف للشؤون القانونية مع إلزامية التعويض.',
                    'student_id' => $student12->id,
                    'staff_id' => $staff->id,
                    'course_id' => null,
                ]);
            });
        }

        // 6. عقوبة إضافية: تنبيه شفهي - منتهية (عودة للطالب 20210008 لتنويع السجل الشخصي)
        $sanctionType6 = SanctionType::where('name', 'تنبيه شفهي')->first() ?? $sanctionType2;
        if ($student8) {
            DB::transaction(function () use ($student8, $staff, $course, $sanctionType6) {
                $this->sanctionRepo->create([
                    'sanction_type_id' => $sanctionType6->id,
                    'status' => 'expired',
                    'issued_date' => now()->subDays(120),
                    'expiry_date' => now()->subDays(119),
                    'notes' => 'عدم الالتزام بالهدوء داخل المكتبة الجامعية.',
                    'student_response' => 'لن يتكرر الأمر.',
                    'staff_response' => 'اكتفينا بالتنبيه الشفهي نظراً لتعاون الطالب.',
                    'student_id' => $student8->id,
                    'staff_id' => $staff->id,
                    'course_id' => $course->id,
                ]);
            });
        }
    }
}
