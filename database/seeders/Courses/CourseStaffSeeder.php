<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Doctor;
use App\Models\TeachingAssistant;
use App\Models\CourseStaff;

class CourseStaffSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['course_code' => 'CS101', 'doctor_name' => 'محمد نور الدين', 'ta_name' => 'رنا باسم العقاد', 'is_advisor' => true],
            ['course_code' => 'CS201', 'doctor_name' => 'سلمى عبد الرحمن', 'ta_name' => 'عمار حسام الخطيب', 'is_advisor' => true],
            ['course_code' => 'MATH101', 'doctor_name' => 'خالد وليد السيد', 'ta_name' => null, 'is_advisor' => false],
            ['course_code' => 'PHY101', 'doctor_name' => 'خالد وليد السيد', 'ta_name' => 'ريم جورج الخوري', 'is_advisor' => false],
            ['course_code' => 'MED201', 'doctor_name' => 'حسام تيسير الحلبي', 'ta_name' => null, 'is_advisor' => true],
            ['course_code' => 'HIST101', 'doctor_name' => 'غسان نبيل الحافظ', 'ta_name' => null, 'is_advisor' => false],
        ];

        foreach ($data as $item) {
            $course = Course::where('code', $item['course_code'])->first();
            if (!$course) continue;

            $doctor = Doctor::whereHas('person', fn($q) => $q->where('full_name', $item['doctor_name']))->first();
            if (!$doctor) continue;

            $ta = null;
            if ($item['ta_name']) {
                $ta = TeachingAssistant::whereHas('person', fn($q) => $q->where('full_name', $item['ta_name']))->first();
            }

            $exists = CourseStaff::where('course_id', $course->id)
                ->where('doctor_id', $doctor->id)
                ->when($ta, fn($q) => $q->where('ta_id', $ta->id))
                ->exists();

            if (!$exists) {
                CourseStaff::create([
                    'course_id' => $course->id,
                    'doctor_id' => $doctor->id,
                    'ta_id' => $ta?->id,
                    'is_advisor' => $item['is_advisor'],
                ]);
            }
        }
    }
}
