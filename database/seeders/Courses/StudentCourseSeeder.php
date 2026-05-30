<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StudentCourseRepositoryInterface;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;

class StudentCourseSeeder extends Seeder
{
    public function __construct(
        private StudentCourseRepositoryInterface $studentCourseRepo,
    ) {}

    public function run(): void
    {
        // -------------------- البيانات الثابتة من dummyData.ts --------------------
        $studentCoursesData = [
            ['student_name' => 'أحمد محمد العلي', 'course_code' => 'CS101', 'credits' => 3, 'status' => 'pass'],
            ['student_name' => 'أحمد محمد العلي', 'course_code' => 'CS201', 'credits' => 4, 'status' => 'pass'],
            ['student_name' => 'فاطمة خالد الحسين', 'course_code' => 'CS101', 'credits' => 3, 'status' => 'pass'],
            ['student_name' => 'يوسف سامر الحموي', 'course_code' => 'MATH101', 'credits' => 3, 'status' => 'fail'],
            ['student_name' => 'نورا علي حسين', 'course_code' => 'PHY101', 'credits' => 4, 'status' => 'pass'],
            ['student_name' => 'لينا جمال عزام', 'course_code' => 'MED201', 'credits' => 5, 'status' => 'pass'],
            ['student_name' => 'رامي عدنان الخطيب', 'course_code' => 'ECO101', 'credits' => 3, 'status' => 'pass'], // تم تغيير helped إلى pass
            ['student_name' => 'دعاء إبراهيم الشيخ', 'course_code' => 'HIST101', 'credits' => 2, 'status' => 'pass'],
        ];

        foreach ($studentCoursesData as $data) {
            $student = Student::whereHas('person', function ($q) use ($data) {
                $q->where('full_name', $data['student_name']);
            })->first();
            $course = Course::where('code', $data['course_code'])->first();

            if ($student && $course) {
                $exists = \App\Models\StudentCourse::where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->exists();
                if (!$exists) {
                    DB::transaction(function () use ($student, $course, $data) {
                        $this->studentCourseRepo->create([
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                            'credits' => $data['credits'],
                            'status' => $data['status'],
                        ]);
                    });
                }
            } else {
            }
        }

        // -------------------- البيانات العشوائية لباقي الطلاب --------------------
        $students = Student::all();
        $courses = Course::all();

        foreach ($students as $student) {
            $departmentCourses = $courses->where('department_id', $student->department_id);

            foreach ($departmentCourses as $course) {
                $exists = \App\Models\StudentCourse::where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->exists();
                if (!$exists) {
                    $status = rand(1, 100) >= 20 ? 'pass' : 'fail';
                    DB::transaction(function () use ($student, $course, $status) {
                        $this->studentCourseRepo->create([
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                            'status' => $status,
                        ]);
                    });
                }
            }
        }
    }
}
