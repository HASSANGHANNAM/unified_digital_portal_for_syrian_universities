<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\StudyPlanCourse;

class StudyPlanCourseSeeder extends Seeder
{
    public function run(): void
    {
        $plan = [

            // هندسة البرمجيات
            'SE301' => ['year' => 3, 'semester' => 1],
            'SE302' => ['year' => 3, 'semester' => 1],
            'SE303' => ['year' => 3, 'semester' => 2],
            'SE401' => ['year' => 4, 'semester' => 1],

            // الذكاء الاصطناعي
            'AI401' => ['year' => 4, 'semester' => 1],
            'AI402' => ['year' => 4, 'semester' => 1],
            'AI403' => ['year' => 4, 'semester' => 2],
            'AI404' => ['year' => 4, 'semester' => 2],

            // الشبكات
            'NET301' => ['year' => 3, 'semester' => 1],
            'NET302' => ['year' => 3, 'semester' => 1],
            'NET303' => ['year' => 3, 'semester' => 2],
            'NET401' => ['year' => 4, 'semester' => 1],

            // الأمن السيبراني
            'SEC301' => ['year' => 3, 'semester' => 1],
            'SEC302' => ['year' => 3, 'semester' => 1],
            'SEC303' => ['year' => 3, 'semester' => 2],
            'SEC401' => ['year' => 4, 'semester' => 1],

            // الجراحة العامة
            'SUR301' => ['year' => 3, 'semester' => 1],
            'SUR302' => ['year' => 3, 'semester' => 1],
            'SUR303' => ['year' => 3, 'semester' => 2],
            'SUR401' => ['year' => 4, 'semester' => 1],

            // طب الأطفال
            'PED301' => ['year' => 3, 'semester' => 1],
            'PED302' => ['year' => 3, 'semester' => 1],
            'PED303' => ['year' => 3, 'semester' => 2],
            'PED401' => ['year' => 4, 'semester' => 1],

            // طب النساء والتوليد
            'OBS301' => ['year' => 3, 'semester' => 1],
            'OBS302' => ['year' => 3, 'semester' => 1],
            'OBS303' => ['year' => 3, 'semester' => 2],
            'OBS401' => ['year' => 4, 'semester' => 1],

            // الفيزياء
            'PHY201' => ['year' => 2, 'semester' => 1],
            'PHY301' => ['year' => 3, 'semester' => 1],
            'PHY302' => ['year' => 3, 'semester' => 2],
            'PHY401' => ['year' => 4, 'semester' => 1],

            // الكيمياء
            'CHM201' => ['year' => 2, 'semester' => 1],
            'CHM301' => ['year' => 3, 'semester' => 1],
            'CHM302' => ['year' => 3, 'semester' => 2],
            'CHM401' => ['year' => 4, 'semester' => 1],

            // الأحياء
            'BIO201' => ['year' => 2, 'semester' => 1],
            'BIO301' => ['year' => 3, 'semester' => 1],
            'BIO302' => ['year' => 3, 'semester' => 2],
            'BIO401' => ['year' => 4, 'semester' => 1],
        ];

        foreach ($plan as $courseCode => $data) {

            $course = Course::where('code', $courseCode)->first();

            if (!$course) {
                continue;
            }

            DB::transaction(function () use ($course, $data) {

                StudyPlanCourse::create([
                    'course_id' => $course->id,
                    'department_id' => $course->department_id,
                    'year' => $data['year'],
                    'semester' => $data['semester'],
                ]);

            });
        }
    }
}
