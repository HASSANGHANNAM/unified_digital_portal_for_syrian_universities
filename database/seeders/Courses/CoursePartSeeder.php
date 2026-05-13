<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\CoursePartRepositoryInterface;
use App\Models\Course;

class CoursePartSeeder extends Seeder
{
    public function __construct(
        private CoursePartRepositoryInterface $coursePartRepo,
    ) {}

    public function run(): void
    {
        foreach (Course::all() as $course) {

            $parts = [

                [
                    'course_id' => $course->id,
                    'name' => 'العملي',
                    'percentage' => 30,
                ],

                [
                    'course_id' => $course->id,
                    'name' => 'النظري',
                    'percentage' => 70,
                ],

            ];

            foreach ($parts as $part) {

                DB::transaction(function () use ($part) {

                    $this->coursePartRepo->create($part);

                });

            }
        }
    }
}
