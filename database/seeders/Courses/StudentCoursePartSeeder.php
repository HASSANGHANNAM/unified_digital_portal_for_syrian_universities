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
        // ==================== 1. البيانات العشوائية (الموجودة أصلاً) ====================
        $studentCourses = StudentCourse::all();

        foreach ($studentCourses as $studentCourse) {
            // جلب أجزاء المقرر التي تحمل اسم 'practical' أو 'theoretical' فقط
            $courseParts = CoursePart::where('course_id', $studentCourse->course_id)
                ->whereIn('name', ['practical', 'theoretical'])
                ->get();

            foreach ($courseParts as $part) {
                // الدرجة حسب نوع الجزء
                $credits = match ($part->name) {
                    'practical' => rand(15, 20),   // العملي من 15 إلى 20
                    'theoretical' => rand(40, 60), // النظري من 40 إلى 60
                    default => 0,
                };

                DB::transaction(function () use ($studentCourse, $part, $credits) {
                    // تجنب التكرار (إضافة شرط)
                    $exists = \App\Models\StudentCoursePart::where('student_course_id', $studentCourse->id)
                        ->where('course_part_id', $part->id)
                        ->exists();
                    if (!$exists) {
                        $this->studentCoursePartRepo->create([
                            'student_course_id' => $studentCourse->id,
                            'course_part_id' => $part->id,
                            'credits' => $credits,
                            'published' => true,
                        ]);
                    }
                });
            }
        }

        // ==================== 2. البيانات الثابتة من dummyData.ts ====================
        $specificStudentCourseParts = [
            [
                'student_name' => 'أحمد محمد العلي',
                'course_code' => 'CS101',
                'course_part_name' => 'أساسيات C++',
                'credits' => 1,
                'published' => true,
            ],
            [
                'student_name' => 'أحمد محمد العلي',
                'course_code' => 'CS101',
                'course_part_name' => 'التعامل مع المصفوفات',
                'credits' => 1,
                'published' => true,
            ],
            [
                'student_name' => 'أحمد محمد العلي',
                'course_code' => 'CS101',
                'course_part_name' => 'البرمجة غرضية التوجه',
                'credits' => 1,
                'published' => true,
            ],
            [
                'student_name' => 'أحمد محمد العلي',
                'course_code' => 'CS201',
                'course_part_name' => 'القوائم المترابطة',
                'credits' => 2,
                'published' => true,
            ],
            [
                'student_name' => 'أحمد محمد العلي',
                'course_code' => 'CS201',
                'course_part_name' => 'الأشجار والرسوم البيانية',
                'credits' => 2,
                'published' => false,
            ],
        ];

        foreach ($specificStudentCourseParts as $data) {
            // جلب الطالب عبر اسمه الكامل
            $student = Student::whereHas('person', function ($q) use ($data) {
                $q->where('full_name', $data['student_name']);
            })->first();

            // جلب المقرر عبر الكود
            $course = Course::where('code', $data['course_code'])->first();

            if ($student && $course) {
                // جلب سجل الطالب لهذا المقرر
                $studentCourse = StudentCourse::where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->first();

                if ($studentCourse) {
                    // جلب جزء المقرر عبر الاسم
                    $coursePart = CoursePart::where('course_id', $course->id)
                        ->where('name', $data['course_part_name'])
                        ->first();

                    if ($coursePart) {
                        // تجنب التكرار
                        $exists = \App\Models\StudentCoursePart::where('student_course_id', $studentCourse->id)
                            ->where('course_part_id', $coursePart->id)
                            ->exists();

                        if (!$exists) {
                            DB::transaction(function () use ($studentCourse, $coursePart, $data) {
                                $this->studentCoursePartRepo->create([
                                    'student_course_id' => $studentCourse->id,
                                    'course_part_id' => $coursePart->id,
                                    'credits' => $data['credits'],
                                    'published' => $data['published'],
                                ]);
                            });
                        }
                    }
                }
            }
        }
    }
}
