<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedulesData = [
            ['course_code' => 'CS101', 'day_of_week' => 1, 'start_time' => '08:00', 'end_time' => '10:00', 'location' => 'مدرج 1 - كلية الهندسة المعلوماتية', 'semester' => 'ربيع 2025', 'year' => 2025],
            ['course_code' => 'CS201', 'day_of_week' => 3, 'start_time' => '10:00', 'end_time' => '12:00', 'location' => 'قاعة 201 - كلية الهندسة المعلوماتية', 'semester' => 'ربيع 2025', 'year' => 2025],
            ['course_code' => 'MATH101', 'day_of_week' => 2, 'start_time' => '09:00', 'end_time' => '11:00', 'location' => 'مدرج العلوم 1', 'semester' => 'ربيع 2025', 'year' => 2025],
            ['course_code' => 'PHY101', 'day_of_week' => 4, 'start_time' => '11:00', 'end_time' => '13:00', 'location' => 'مخبر الفيزياء', 'semester' => 'ربيع 2025', 'year' => 2025],
            ['course_code' => 'MED201', 'day_of_week' => 1, 'start_time' => '14:00', 'end_time' => '16:00', 'location' => 'مدرج كلية الطب', 'semester' => 'ربيع 2025', 'year' => 2025],
            ['course_code' => 'ECO101', 'day_of_week' => 5, 'start_time' => '08:00', 'end_time' => '10:00', 'location' => 'قاعة 104 اقتصاد', 'semester' => 'ربيع 2025', 'year' => 2025],
        ];

        foreach ($schedulesData as $data) {
            $course = Course::where('code', $data['course_code'])->first();
            if (!$course) continue;

            Schedule::firstOrCreate([
                'course_id' => $course->id,
                'day_of_week' => $data['day_of_week'],
                'start_time' => $data['start_time'],
            ], [
                'end_time' => $data['end_time'],
                'location' => $data['location'],
                'semester' => $data['semester'],
                'year' => $data['year'],
            ]);
        }
    }
}