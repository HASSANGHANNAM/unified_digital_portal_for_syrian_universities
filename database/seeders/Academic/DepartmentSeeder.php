<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use App\Models\College;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $informatics = College::where('name', 'كلية الهندسة المعلوماتية')->first();
        $medicine = College::where('name', 'كلية الطب البشري')->first();
        $sciences = College::where('name', 'كلية العلوم')->first();
        $arts = College::where('name', 'كلية الآداب والعلوم الإنسانية')->first();
        $economics = College::where('name', 'كلية الاقتصاد')->first();

        $departments = [
            // الأقسام الأكاديمية الرئيسية (بدون كلمة "قسم")
            ['name' => 'هندسة البرمجيات', 'college_id' => $informatics->id],
            ['name' => 'الذكاء الاصطناعي', 'college_id' => $informatics->id],
            ['name' => 'الشبكات', 'college_id' => $informatics->id],
            ['name' => 'الأمن السيبراني', 'college_id' => $informatics->id],
            ['name' => 'الجراحة العامة', 'college_id' => $medicine->id],
            ['name' => 'طب الأطفال', 'college_id' => $medicine->id],
            ['name' => 'طب النساء والتوليد', 'college_id' => $medicine->id],
            ['name' => 'فيزياء', 'college_id' => $sciences->id],
            ['name' => 'الكيمياء', 'college_id' => $sciences->id],
            ['name' => 'الأحياء', 'college_id' => $sciences->id],
            ['name' => 'رياضيات', 'college_id' => $sciences->id],
            ['name' => 'لغة عربية', 'college_id' => $arts->id],
            ['name' => 'تاريخ', 'college_id' => $arts->id],
            ['name' => 'إدارة أعمال', 'college_id' => $economics->id],
            ['name' => 'محاسبة', 'college_id' => $economics->id],
            ['name' => 'طب بشري', 'college_id' => $medicine->id],
            ['name' => 'أمراض باطنية', 'college_id' => $medicine->id],

            // أقسام إضافية تحتوي على "قسم" لضمان التوافق (اختياري)
            ['name' => 'قسم الفيزياء', 'college_id' => $sciences->id],
            ['name' => 'قسم الرياضيات', 'college_id' => $sciences->id],
            ['name' => 'قسم اللغة العربية', 'college_id' => $arts->id],
            ['name' => 'قسم التاريخ', 'college_id' => $arts->id],
            ['name' => 'قسم إدارة الأعمال', 'college_id' => $economics->id],
            ['name' => 'قسم المحاسبة', 'college_id' => $economics->id],
            ['name' => 'قسم الأمراض الباطنية', 'college_id' => $medicine->id],
            ['name' => 'قسم الجراحة', 'college_id' => $medicine->id],
            ['name' => 'الفيزياء', 'college_id' => $sciences->id],

        ];

        foreach ($departments as $dept) {
            \App\Models\Department::firstOrCreate(
                ['name' => $dept['name'], 'college_id' => $dept['college_id']],
                $dept
            );
        }
    }
}
