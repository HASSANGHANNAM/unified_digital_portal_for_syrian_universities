<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\UniversalCourse;

class UniversalCourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [

            // هندسة البرمجيات
            ['name' => 'قواعد البيانات'],
            ['name' => 'هندسة المتطلبات'],
            ['name' => 'تصميم واجهات المستخدم'],
            ['name' => 'إدارة المشاريع البرمجية'],

            // الذكاء الاصطناعي
            ['name' => 'الذكاء الاصطناعي'],
            ['name' => 'تعلم الآلة'],
            ['name' => 'معالجة اللغة الطبيعية'],
            ['name' => 'رؤية الحاسوب'],

            // الشبكات
            ['name' => 'شبكات الحاسوب'],
            ['name' => 'بروتوكولات الشبكات'],
            ['name' => 'الشبكات اللاسلكية'],
            ['name' => 'إدارة الشبكات'],

            // الأمن السيبراني
            ['name' => 'أمن الشبكات'],
            ['name' => 'التشفير'],
            ['name' => 'أمن التطبيقات'],
            ['name' => 'الاختراق الأخلاقي'],

            // الجراحة العامة
            ['name' => 'أساسيات الجراحة'],
            ['name' => 'الجراحة العامة'],
            ['name' => 'جراحة الطوارئ'],
            ['name' => 'تقنيات الجراحة الحديثة'],

            // طب الأطفال
            ['name' => 'أمراض الأطفال'],
            ['name' => 'تغذية الأطفال'],
            ['name' => 'صحة الطفل'],
            ['name' => 'أدوية الأطفال'],

            // طب النساء والتوليد
            ['name' => 'صحة المرأة'],
            ['name' => 'التوليد'],
            ['name' => 'أمراض النساء'],
            ['name' => 'تنظيم الأسرة'],

            // الفيزياء
            ['name' => 'الفيزياء العامة'],
            ['name' => 'الفيزياء الحديثة'],
            ['name' => 'الكهرومغناطيسية'],
            ['name' => 'فيزياء الكم'],

            // الكيمياء
            ['name' => 'الكيمياء العامة'],
            ['name' => 'الكيمياء العضوية'],
            ['name' => 'الكيمياء التحليلية'],
            ['name' => 'الكيمياء الحيوية'],

            // الأحياء
            ['name' => 'الأحياء العامة'],
            ['name' => 'الوراثة'],
            ['name' => 'علم الخلية'],
            ['name' => 'علم البيئة'],


        ];

    foreach ($courses as $course) {
        DB::transaction(function () use ($course) {

            $exists = UniversalCourse::where('name', $course['name'])->exists();

            if (!$exists) {
                UniversalCourse::create($course);
            }
        });
    }


    }
}
