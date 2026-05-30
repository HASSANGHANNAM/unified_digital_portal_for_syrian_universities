<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Group;

class ScheduleGroupSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['course_code' => 'CS101', 'group_name' => 'المجموعة الأولى - هندسة برمجيات'],
            ['course_code' => 'CS201', 'group_name' => 'المجموعة الثانية - ذكاء صنعي'],
            ['course_code' => 'PHY101', 'group_name' => 'مجموعة المختبر - فيزياء'],
        ];

        foreach ($data as $item) {
            $schedule = Schedule::whereHas('course', fn($q) => $q->where('code', $item['course_code']))->first();
            $group = Group::where('name', $item['group_name'])->first();
            if ($schedule && $group) {
                // استخدام DB::table لتجنب الأعمدة الزمنية
                DB::table('schedule_group')->insertOrIgnore([
                    'schedule_id' => $schedule->id,
                    'group_id' => $group->id,
                ]);
            }
        }
    }
}
