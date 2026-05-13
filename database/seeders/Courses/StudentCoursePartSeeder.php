<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StudentCoursePartRepositoryInterface;
use App\Models\StudentCourse;
use App\Models\CoursePart;

class StudentCoursePartSeeder extends Seeder
{
    public function __construct(
        private StudentCoursePartRepositoryInterface $studentCoursePartRepo,
    ) {}

    public function run(): void
    {
        $studentCourses = StudentCourse::all();

        foreach ($studentCourses as $studentCourse) {

            $courseParts = CoursePart::where('course_id', $studentCourse->course_id)
                ->whereIn('name', ['عملي', 'نظري'])
                ->get();

            foreach ($courseParts as $part) {

                // الدرجة حسب نوع الجزء
                $score = match ($part->name) {
                    'عملي' => rand(15, 20),   // العملي من 15 إلى 20
                    'نظري' => rand(40, 60),   // النظري من 40 إلى 60
                    default => 0,
                };

                DB::transaction(function () use ($studentCourse, $part, $score) {

                    $this->studentCoursePartRepo->create([
                        'student_course_id' => $studentCourse->id,
                        'course_part_id' => $part->id,
                        'score' => $score,
                        'published' => true,
                    ]);

                });

            }
        }

    }
}
