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
        // ==================== 1. الأجزاء الافتراضية لكل مقرر (عملي/نظري) ====================
        foreach (Course::all() as $course) {
            $defaultParts = [
                [
                    'course_id' => $course->id,
                    'name' => 'practical',
                    'percentage' => 30,
                ],
                [
                    'course_id' => $course->id,
                    'name' => 'theoretical',
                    'percentage' => 70,
                ],
            ];

            foreach ($defaultParts as $part) {
                DB::transaction(function () use ($part) {
                    // تجنب التكرار
                    $exists = \App\Models\CoursePart::where('course_id', $part['course_id'])
                        ->where('name', $part['name'])
                        ->exists();
                    if (!$exists) {
                        $this->coursePartRepo->create($part);
                    }
                });
            }
        }

        // ==================== 2. الأجزاء الثابتة من dummyData.ts ====================
        $staticParts = [
            ['course_code' => 'CS101', 'name' => 'أساسيات C++', 'percentage' => 30],
            ['course_code' => 'CS101', 'name' => 'التعامل مع المصفوفات', 'percentage' => 30],
            ['course_code' => 'CS101', 'name' => 'البرمجة غرضية التوجه', 'percentage' => 40],
            ['course_code' => 'CS201', 'name' => 'القوائم المترابطة', 'percentage' => 50],
            ['course_code' => 'CS201', 'name' => 'الأشجار والرسوم البيانية', 'percentage' => 50],
            ['course_code' => 'MATH101', 'name' => 'النهايات والتفاضل', 'percentage' => 100],
            ['course_code' => 'PHY101', 'name' => 'الميكانيك', 'percentage' => 50],
            ['course_code' => 'PHY101', 'name' => 'الكهرباء والمغناطيسية', 'percentage' => 50],
        ];

        foreach ($staticParts as $part) {
            // جلب course_id عبر course_code
            $course = Course::where('code', $part['course_code'])->first();
            if (!$course) {
                continue;
            }

            DB::transaction(function () use ($course, $part) {
                $exists = \App\Models\CoursePart::where('course_id', $course->id)
                    ->where('name', $part['name'])
                    ->exists();
                if (!$exists) {
                    $this->coursePartRepo->create([
                        'course_id' => $course->id,
                        'name' => $part['name'],
                        'percentage' => $part['percentage'],
                    ]);
                }
            });
        }
    }
}
