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

            ['student_name' => 'خالد وليد السيد', 'group_name' => 'المجموعة الأولى - هندسة برمجيات'],
            ['student_name' => 'رنا باسم العقاد', 'group_name' => 'المجموعة الأولى - هندسة برمجيات'],

            ['student_name' => 'عمار حسام الخطيب', 'group_name' => 'المجموعة الثانية - هندسة برمجيات'],
            ['student_name' => 'لينا جمال عزام', 'group_name' => 'المجموعة الثانية - هندسة برمجيات'],

            ['student_name' => 'رامي عدنان الخطيب', 'group_name' => 'المجموعة الثالثة - هندسة برمجيات'],
            ['student_name' => 'هبة الله مصطفى', 'group_name' => 'المجموعة الثالثة - هندسة برمجيات'],
            ['student_name' => 'عمر خالد', 'group_name' => 'المجموعة الثالثة - هندسة برمجيات'],

        ];

        foreach ($data as $item) {
            $student = Student::whereHas('person', fn($q) => $q->where('full_name', $item['student_name']))->first();
            $group = Group::where('name', $item['group_name'])->first();
            if ($student && $group) {
                DB::table('student_group')->insertOrIgnore([
                    'student_id' => $student->id,
                    'group_id' => $group->id,
                ]);
            }
        }
    }
}
