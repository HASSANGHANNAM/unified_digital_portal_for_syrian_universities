<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Department;
use App\Models\StudyPlanCourse;
use App\Models\StudentCourse; // أو أي جدول تستخدمه لربط الطالب بالمواد

class StudentCourseSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();
        $departmentsByName = $departments->keyBy('name');

        $basicDept = $departmentsByName['العلوم الأساسية'] ?? null;

        if (!$basicDept) {
            $this->command->error('❌ قسم العلوم الأساسية غير موجود!');
            return;
        }
        $students = Student::with(['person', 'college'])->get();

        if ($students->isEmpty()) {
            $this->command->warn('⚠️ لا يوجد طلاب لتسجيلهم.');
            return;
        }

        $totalEnrolled = 0;

        foreach ($students as $student) {
            $this->command->info("📝 جاري معالجة الطالب: {$student->person->full_name} (السنة {$student->current_year})");

            // تحديد القسم المناسب حسب السنة الدراسية
            // السنوات 1-3: قسم العلوم الأساسية
            // السنوات 4-5: القسم التخصصي للطالب
            $departmentId = $student->current_year <= 3
                ? $basicDept->id
                : $student->department_id;

            if (!$departmentId) {
                $this->command->warn("⚠️ الطالب {$student->person->full_name} ليس لديه قسم محدد. تخطي.");
                continue;
            }

            // بناء قائمة المواد التي يجب أن يسجل فيها الطالب
            $coursesToEnroll = collect();

            if ($student->current_year <= 3) {
                // السنوات 1-3: فقط مواد قسم العلوم الأساسية حتى سنته الحالية
                $coursesToEnroll = StudyPlanCourse::where('department_id', $departmentId)
                    ->where('year', '<=', $student->current_year)
                    ->with('course')
                    ->get();
            } else {
                // السنوات 4-5: مواد قسم العلوم الأساسية (1-3) + مواد قسمه التخصصي (4-5)
                // مواد العلوم الأساسية (1-3)
                $basicCourses = StudyPlanCourse::where('department_id', $basicDept->id)
                    ->where('year', '<=', 3)
                    ->with('course')
                    ->get();

                // مواد القسم التخصصي (4-5) حتى سنته الحالية
                $specializedCourses = StudyPlanCourse::where('department_id', $student->department_id)
                    ->where('year', '<=', $student->current_year)
                    ->where('year', '>=', 4)
                    ->with('course')
                    ->get();

                $coursesToEnroll = $basicCourses->merge($specializedCourses);
            }

            if ($coursesToEnroll->isEmpty()) {
                $this->command->warn("⚠️ لا توجد مواد للطالب {$student->person->full_name} في السنة {$student->current_year}.");
                continue;
            }

            // تسجيل الطالب في المواد
            $enrolledCount = 0;
            foreach ($coursesToEnroll as $plan) {
                // استخدام firstOrCreate لتجنب التكرار
                $result = StudentCourse::firstOrCreate([
                    'student_id' => $student->id,
                    'course_id'  => $plan->course_id,
                    'academic_year' => $student->enrollment_year + $plan->year - 1, // السنة الأكاديمية الفعلية
                    'semester'   => $plan->semester,
                ]);

                if ($result->wasRecentlyCreated) {
                    $enrolledCount++;
                }
            }

            $totalEnrolled += $enrolledCount;
            $this->command->info("✅ تم تسجيل {$enrolledCount} مادة للطالب {$student->person->full_name}");
        }

        $this->command->info("🎉 تم الانتهاء من تسجيل جميع الطلاب. إجمالي المواد المسجلة: {$totalEnrolled}");
    }
}
