<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Group;

class StudentGroupSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['student_name' => 'أحمد محمد العلي', 'group_name' => 'المجموعة الأولى - هندسة برمجيات'],
            ['student_name' => 'فاطمة خالد الحسين', 'group_name' => 'المجموعة الثانية - ذكاء صنعي'],
            ['student_name' => 'نورا علي حسين', 'group_name' => 'مجموعة المختبر - فيزياء'],
        ];

        foreach ($data as $item) {
            $student = Student::whereHas('person', fn($q) => $q->where('full_name', $item['student_name']))->first();
            $group = Group::where('name', $item['group_name'])->first();
            if ($student && $group) {
                // استخدام DB::table لتجنب مشكلة الأعمدة الزمنية
                DB::table('student_group')->insertOrIgnore([
                    'student_id' => $student->id,
                    'group_id' => $group->id,
                ]);
            }
        }
    }
}
