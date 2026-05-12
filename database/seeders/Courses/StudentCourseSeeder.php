<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StudentCourseRepositoryInterface;
use App\Models\Student;
use App\Models\Course;

class StudentCourseSeeder extends Seeder
{
    public function __construct(
        private StudentCourseRepositoryInterface $studentCourseRepo,
    ) {}

    public function run(): void
    {
        $students = Student::all();
        $courses = Course::all();

        foreach ($students as $student) {

            $departmentCourses = $courses->where('department_id', $student->department_id);

            foreach ($departmentCourses as $course) {

                $status = rand(1, 100) >= 20 ? 'fail' : 'pass';

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
