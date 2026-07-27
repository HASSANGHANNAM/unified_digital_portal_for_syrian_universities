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

            [
                'name' => 'اللغة الانكليزية 1',
                'image' => null
            ],
            [
                'name' => 'اللغة الانكليزية 2',
                'image' => null
            ],
            [
                'name' => 'اللغة الانكليزية 3',
                'image' => null
            ],
            [
                'name' => 'اللغة الانكليزية 4',
                'image' => null
            ],
            [
                'name' => 'البرمجة 1',
                'image' => null
            ],
            [
                'name' => 'البرمجة 2',
                'image' => null
            ],
            [
                'name' => 'البرمجة 3',
                'image' => null
            ],
            [
                'name' => 'الجبر العام',
                'image' => null
            ],
            [
                'name' => 'الجبر الخطي',
                'image' => null
            ],
            [
                'name' => 'تحليل 1',
                'image' => null
            ],
            [
                'name' => 'تحليل 2',
                'image' => null
            ],
            [
                'name' => 'تحليل 3',
                'image' => null
            ],
            [
                'name' => 'تحليل عددي',
                'image' => null
            ],
            [
                'name' => 'الاحتمالات و الإحصاء',
                'image' => null
            ],
            [
                'name' => 'مبادئ عمل الحاسوب',
                'image' => null
            ],
            [
                'name' => 'بنيان الحواسيب 1',
                'image' => null
            ],
            [
                'name' => 'بنيان الحواسيب 2',
                'image' => null
            ],
            [
                'name' => 'قواعد المعطيات 1',
                'image' => null
            ],
            [
                'name' => 'قواعد المعطيات 2',
                'image' => null
            ],
            [
                'name' => 'قواعد المعطيات المتقدمة',
                'image' => null
            ],
            [
                'name' => 'قواعد البيانات',
                'image' => null
            ],
            [
                'name' => 'الفيزياء',
                'image' => null
            ],
            [
                'name' => 'اللغة العربية',
                'image' => null
            ],
            [
                'name' => 'الدارات الكهربائية',
                'image' => null
            ],
            [
                'name' => 'الدارات المطقية',
                'image' => null
            ],
            [
                'name' => 'الخوارزميات وبنى المعطيات 1',
                'image' => null
            ],
            [
                'name' => 'الخوارزميات وبنى المعطيات 2',
                'image' => null
            ],
            [
                'name' => 'الاتصالات الرقمية ',
                'image' => null
            ],
            [
                'name' => 'مهارات التواصل',
                'image' => null
            ],
            [
                'name' => 'بحوث العمليات',
                'image' => null
            ],
            [
                'name' => 'لغات البرمجة',
                'image' => null
            ],
            [
                'name' => 'مبادئ الذكاء الصنعي',
                'image' => null
            ],
            [
                'name' => 'أساسيات الشبكات المعلوماتية ',
                'image' => null
            ],
            [
                'name' => 'اللغات الصورية',
                'image' => null
            ],
            [
                'name' => 'بيانيات حاسوبية',
                'image' => null
            ],
            [
                'name' => 'حسابات علمية',
                'image' => null
            ],
            [
                'name' => 'المشروع 1',
                'image' => null
            ],
            [
                'name' => 'المشروع 2',
                'image' => null
            ],
            [
                'name' => 'المشروع 3',
                'image' => null
            ],
            [
                'name' => 'بروتوكولات الاتصال الحاسوبية',
                'image' => null
            ],
            [
                'name' => 'خوارزميات البحث الذكية',
                'image' => null
            ],
            [
                'name' => 'نظم تشغيل 1',
                'image' => null
            ],
            [
                'name' => 'نظم تشغيل 2',
                'image' => null
            ],
            [
                'name' => 'البرمجة التفرعية',
                'image' => null
            ],
            [
                'name' => 'التسويق',
                'image' => null
            ],
            [
                'name' => 'الاقتصاد والإدارة في المؤسسة',
                'image' => null
            ],
            [
                'name' => 'إدارة المشاريع',
                'image' => null
            ],
            [
                'name' => 'هندسة الرمجيات 1',
                'image' => null
            ],
            [
                'name' => 'هندسة الرمجيات 2',
                'image' => null
            ],
            [
                'name' => 'هندسة الرمجيات 3',
                'image' => null
            ],
            [
                'name' => 'برمجة التطبيقات الشبكية',
                'image' => null
            ],
            [
                'name' => 'نظم وساءط متعددة وفائقة',
                'image' => null
            ],
            [
                'name' => 'الحقائق الافتراضية',
                'image' => null
            ],
            [
                'name' => 'المترجمات 1',
                'image' => null
            ],
            [
                'name' => 'مشروع المترجمات',
                'image' => null
            ],
            [
                'name' => 'نظم قواعد المعرفة',
                'image' => null
            ],
            [
                'name' => 'الشبكات العصبونية ',
                'image' => null
            ],
            [
                'name' => 'نمذجة ومحاكاة النظم الشبكية',
                'image' => null
            ],
            [
                'name' => 'تصميم الشبكات الحاسوبية',
                'image' => null
            ],
            [
                'name' => 'أمن نظم المعلومات',
                'image' => null
            ],
            [
                'name' => 'النظم والتطبيقات الموزعة',
                'image' => null
            ],
            [
                'name' => 'معالجة اللغات الطبيعية',
                'image' => null
            ],
            [
                'name' => 'الروبوتية',
                'image' => null
            ],
            [
                'name' => 'تطبيقات الانترنت',
                'image' => null
            ],
            [
                'name' => 'نظم الزمن الحقيقي',
                'image' => null
            ],
            [
                'name' => 'إدارة الشبكات الحاسوبية',
                'image' => null
            ],
            [
                'name' => 'أمن الشبكات الحاسوبية',
                'image' => null
            ],
            [
                'name' => 'التعلم التلقائي',
                'image' => null
            ],
            [
                'name' => 'الرؤيا الحاسوبية ',
                'image' => null
            ],
            [
                'name' => 'استكشاف المعرفة',
                'image' => null
            ],
            [
                'name' => 'نظم البحث عن الملومات',
                'image' => null
            ],
            [
                'name' => 'هندسة نظم المعلومات',
                'image' => null
            ],




            [
                'name' => 'الجراحة 1',
                'image' => null
            ],
            [
                'name' => 'الجراحة 2',
                'image' => null
            ],
            [
                'name' => 'الجراحة 3',
                'image' => null
            ],
            [
                'name' => 'الجراحة 4',
                'image' => null
            ],

            [
                'name' => 'الأطفال 1',
                'image' => null
            ],

            [
                'name' => 'الأطفال 2',
                'image' => null
            ],

            [
                'name' => 'النسائية',
                'image' => null
            ],
            [
                'name' => 'طب الأسرة',
                'image' => null
            ],
            [
                'name' => 'الفيزياء العامة',
                'image' => null
            ],
            [
                'name' => 'الكيمياء العامة',
                'image' => null
            ],
            [
                'name' => 'الكيمياء العضوية',
                'image' => null
            ],
            [
                'name' => 'الكيمياء التحليلية',
                'image' => null
            ],
            [
                'name' => 'الأحياء العامة',
                'image' => null
            ],

            [
                'name' => 'علم الخلية',
                'image' => null
            ],
            [
                'name' => 'الوراثة',
                'image' => null
            ],
            [
                'name' => 'علم البيئة',
                'image' => null
            ],
            [
                'name' => 'مبادئ الاقتصاد',
                'image' => null
            ],
            [
                'name' => 'التشريح 1',
                'image' => null
            ],
            [
                'name' => 'التشريح 2',
                'image' => null
            ],
            [
                'name' => 'التشريح 3',
                'image' => null
            ],
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
