<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StudentCoursePartRepositoryInterface;
use App\Models\StudentCourse;
use App\Models\CoursePart;
use App\Models\Student;
use App\Models\Course;

class StudentCoursePartSeeder extends Seeder
{
    public function __construct(
        private StudentCoursePartRepositoryInterface $studentCoursePartRepo,
    ) {}

    public function run(): void
    {
        // ==================== 1. جلب جميع سجلات الطلاب في المواد ====================
        $studentCourses = StudentCourse::with(['student.person', 'course'])->get();

        $this->command->info("📊 تم العثور على " . $studentCourses->count() . " سجل طالب-مادة");

        $totalCreated = 0;

        foreach ($studentCourses as $studentCourse) {
            // جلب جميع أجزاء المقرر المرتبطة بهذه المادة
            $courseParts = CoursePart::where('course_id', $studentCourse->course_id)->get();

            if ($courseParts->isEmpty()) {
                $this->command->warn("⚠️ لا توجد أجزاء للمادة: {$studentCourse->course->code}");
                continue;
            }

            $studentName = $studentCourse->student->person->full_name ?? 'غير معروف';
            $courseCode = $studentCourse->course->code ?? 'غير معروف';

            foreach ($courseParts as $part) {
                // تحديد الدرجة الافتراضية حسب اسم الجزء
                $credits = match ($part->name) {
                    'practical' => rand(15, 20),   // العملي من 15 إلى 20
                    'theoretical' => rand(40, 60), // النظري من 40 إلى 60
                    default => rand(10, 50),       // أي جزء آخر
                };

                // تجنب التكرار
                $exists = \App\Models\StudentCoursePart::where('student_course_id', $studentCourse->id)
                    ->where('course_part_id', $part->id)
                    ->exists();

                if (!$exists) {
                    DB::transaction(function () use ($studentCourse, $part, $credits) {
                        $this->studentCoursePartRepo->create([
                            'student_course_id' => $studentCourse->id,
                            'course_part_id' => $part->id,
                            'credits' => $credits,
                            'published' => true,
                        ]);
                    });
                    $totalCreated++;
                }
            }

            $this->command->info("✅ تم ربط {$courseParts->count()} جزء للطالب {$studentName} في المادة {$courseCode}");
        }

        $this->command->info("🎉 تم إنشاء {$totalCreated} سجل جديد في StudentCoursePart");

        // ==================== 2. (اختياري) بيانات مخصصة من المصفوفة ====================
        $this->command->info("📝 جاري معالجة البيانات المخصصة...");
        // $this->seedCustomData();

        $this->command->info("✅ تم الانتهاء من تشغيل السيدر بالكامل!");
    }

    /**
     * بيانات مخصصة من المصفوفة (لحالات خاصة)
     */
    // private function seedCustomData(): void
    // {
    //     $specificStudentCourseParts = [
    //         [
    //             'student_name' => 'أحمد محمد العلي',
    //             'course_code' => 'CS101',
    //             'course_part_name' => 'أساسيات C++',
    //             'credits' => 1,
    //             'published' => true,
    //         ],
    //         [
    //             'student_name' => 'أحمد محمد العلي',
    //             'course_code' => 'CS101',
    //             'course_part_name' => 'التعامل مع المصفوفات',
    //             'credits' => 1,
    //             'published' => true,
    //         ],
    //         [
    //             'student_name' => 'أحمد محمد العلي',
    //             'course_code' => 'CS101',
    //             'course_part_name' => 'البرمجة غرضية التوجه',
    //             'credits' => 1,
    //             'published' => true,
    //         ],
    //         [
    //             'student_name' => 'أحمد محمد العلي',
    //             'course_code' => 'CS201',
    //             'course_part_name' => 'القوائم المترابطة',
    //             'credits' => 2,
    //             'published' => true,
    //         ],
    //         [
    //             'student_name' => 'أحمد محمد العلي',
    //             'course_code' => 'CS201',
    //             'course_part_name' => 'الأشجار والرسوم البيانية',
    //             'credits' => 2,
    //             'published' => false,
    //         ],
    //     ];

    //     foreach ($specificStudentCourseParts as $data) {
    //         // جلب الطالب
    //         $student = Student::whereHas('person', function ($q) use ($data) {
    //             $q->where('full_name', $data['student_name']);
    //         })->first();

    //         // جلب المادة
    //         $course = Course::where('code', $data['course_code'])->first();

    //         if ($student && $course) {
    //             $studentCourse = StudentCourse::where('student_id', $student->id)
    //                 ->where('course_id', $course->id)
    //                 ->first();

    //             if ($studentCourse) {
    //                 $coursePart = CoursePart::where('course_id', $course->id)
    //                     ->where('name', $data['course_part_name'])
    //                     ->first();

    //                 if ($coursePart) {
    //                     $exists = \App\Models\StudentCoursePart::where('student_course_id', $studentCourse->id)
    //                         ->where('course_part_id', $coursePart->id)
    //                         ->exists();

    //                     if (!$exists) {
    //                         DB::transaction(function () use ($studentCourse, $coursePart, $data) {
    //                             $this->studentCoursePartRepo->create([
    //                                 'student_course_id' => $studentCourse->id,
    //                                 'course_part_id' => $coursePart->id,
    //                                 'credits' => $data['credits'],
    //                                 'published' => $data['published'],
    //                             ]);
    //                         });
    //                         $this->command->info("✅ تم إضافة جزء مخصص: {$data['course_part_name']} للطالب {$data['student_name']}");
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }
}
