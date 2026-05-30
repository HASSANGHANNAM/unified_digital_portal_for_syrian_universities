<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Lecture;

class LectureSeeder extends Seeder
{
    public function run(): void
    {
        $lecturesData = [
            ['course_code' => 'CS101', 'title' => 'مقدمة عن لغة ++C', 'file_url' => '/uploads/lec1_intro_cpp.pdf', 'upload_date' => '2025-01-15', 'type' => 'pdf', 'order_index' => 1],
            ['course_code' => 'CS101', 'title' => 'المتحولات وأنواع البيانات', 'file_url' => '/uploads/lec2_variables.mp4', 'upload_date' => '2025-01-20', 'type' => 'video', 'order_index' => 2],
            ['course_code' => 'CS101', 'title' => 'الجمل الشرطية', 'file_url' => '/uploads/lec3_conditionals.pdf', 'upload_date' => '2025-02-01', 'type' => 'pdf', 'order_index' => 3],
            ['course_code' => 'CS201', 'title' => 'القوائم المترابطة الأحادية', 'file_url' => '/uploads/lec4_linked_list.mp4', 'upload_date' => '2025-02-10', 'type' => 'video', 'order_index' => 1],
            ['course_code' => 'MATH101', 'title' => 'مبدأ النهايات', 'file_url' => '/uploads/calc_limits.pdf', 'upload_date' => '2025-01-10', 'type' => 'pdf', 'order_index' => 1],
            ['course_code' => 'PHY101', 'title' => 'قوانين نيوتن', 'file_url' => '/uploads/newton_laws.mp4', 'upload_date' => '2025-01-12', 'type' => 'video', 'order_index' => 1],
        ];

        foreach ($lecturesData as $data) {
            $course = Course::where('code', $data['course_code'])->first();
            if (!$course) continue;

            Lecture::firstOrCreate([
                'course_id' => $course->id,
                'title' => $data['title'],
            ], [
                'file_url' => $data['file_url'],
                'upload_date' => $data['upload_date'],
                'type' => $data['type'],
                'order_index' => $data['order_index'],
            ]);
        }
    }
}