<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\CoursePartRepositoryInterface;
use App\Models\Course;

class CoursePartSeeder extends Seeder
{
    public function __construct(
        private CoursePartRepositoryInterface $coursePartRepo,
    ) {}

    public function run(): void
    {
        $staticParts = [
            // المادة 1: اللغة الانكليزية 1
            ['course_id' => 1, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 1, 'name' => 'نظري', 'percentage' => 70],
            // المادة 2: اللغة الانكليزية 2
            ['course_id' => 2, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 2, 'name' => 'نظري', 'percentage' => 70],
            // المادة 3: اللغة الانكليزية 3
            ['course_id' => 3, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 3, 'name' => 'نظري', 'percentage' => 70],
            // المادة 4: اللغة الانكليزية 4
            ['course_id' => 4, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 4, 'name' => 'نظري', 'percentage' => 70],
            // المادة 5: البرمجة 1
            ['course_id' => 5, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 5, 'name' => 'نظري', 'percentage' => 70],
            // المادة 6: البرمجة 2
            ['course_id' => 6, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 6, 'name' => 'نظري', 'percentage' => 70],
            // المادة 7: البرمجة 3
            ['course_id' => 7, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 7, 'name' => 'نظري', 'percentage' => 70],
            // المادة 8: الجبر العام
            ['course_id' => 8, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 8, 'name' => 'نظري', 'percentage' => 70],
            // المادة 9: الجبر الخطي
            ['course_id' => 9, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 9, 'name' => 'نظري', 'percentage' => 70],
            // المادة 10: تحليل 1
            ['course_id' => 10, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 10, 'name' => 'نظري', 'percentage' => 70],
            // المادة 11: تحليل 2
            ['course_id' => 11, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 11, 'name' => 'نظري', 'percentage' => 70],
            // المادة 12: تحليل 3
            ['course_id' => 12, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 12, 'name' => 'نظري', 'percentage' => 70],
            // المادة 13: تحليل عددي
            ['course_id' => 13, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 13, 'name' => 'نظري', 'percentage' => 70],
            // المادة 14: الاحتمالات و الإحصاء
            ['course_id' => 14, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 14, 'name' => 'نظري', 'percentage' => 70],
            // المادة 15: مبادئ عمل الحاسوب
            ['course_id' => 15, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 15, 'name' => 'نظري', 'percentage' => 70],
            // المادة 16: بنيان الحواسيب 1
            ['course_id' => 16, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 16, 'name' => 'نظري', 'percentage' => 70],
            // المادة 17: بنيان الحواسيب 2
            ['course_id' => 17, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 17, 'name' => 'نظري', 'percentage' => 70],
            // المادة 18: قواعد المعطيات 1
            ['course_id' => 18, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 18, 'name' => 'نظري', 'percentage' => 70],
            // المادة 19: قواعد المعطيات 2
            ['course_id' => 19, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 19, 'name' => 'نظري', 'percentage' => 70],
            // المادة 20: قواعد المعطيات المتقدمة
            ['course_id' => 20, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 20, 'name' => 'نظري', 'percentage' => 70],
            // المادة 21: قواعد البيانات
            ['course_id' => 21, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 21, 'name' => 'نظري', 'percentage' => 70],
            // المادة 22: الفيزياء
            ['course_id' => 22, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 22, 'name' => 'نظري', 'percentage' => 70],
            // المادة 23: اللغة العربية
            ['course_id' => 23, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 23, 'name' => 'نظري', 'percentage' => 70],
            // المادة 24: الدارات الكهربائية
            ['course_id' => 24, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 24, 'name' => 'نظري', 'percentage' => 70],
            // المادة 25: الدارات المطقية
            ['course_id' => 25, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 25, 'name' => 'نظري', 'percentage' => 70],
            // المادة 26: الخوارزميات وبنى المعطيات 1
            ['course_id' => 26, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 26, 'name' => 'نظري', 'percentage' => 70],
            // المادة 27: الخوارزميات وبنى المعطيات 2
            ['course_id' => 27, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 27, 'name' => 'نظري', 'percentage' => 70],
            // المادة 28: الاتصالات الرقمية
            ['course_id' => 28, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 28, 'name' => 'نظري', 'percentage' => 70],
            // المادة 29: مهارات التواصل
            ['course_id' => 29, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 29, 'name' => 'نظري', 'percentage' => 70],
            // المادة 30: بحوث العمليات
            ['course_id' => 30, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 30, 'name' => 'نظري', 'percentage' => 70],
            // المادة 31: لغات البرمجة
            ['course_id' => 31, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 31, 'name' => 'نظري', 'percentage' => 70],
            // المادة 32: مبادئ الذكاء الصنعي
            ['course_id' => 32, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 32, 'name' => 'نظري', 'percentage' => 70],
            // المادة 33: أساسيات الشبكات المعلوماتية
            ['course_id' => 33, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 33, 'name' => 'نظري', 'percentage' => 70],
            // المادة 34: اللغات الصورية
            ['course_id' => 34, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 34, 'name' => 'نظري', 'percentage' => 70],
            // المادة 35: بيانيات حاسوبية
            ['course_id' => 35, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 35, 'name' => 'نظري', 'percentage' => 70],
            // المادة 36: حسابات علمية
            ['course_id' => 36, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 36, 'name' => 'نظري', 'percentage' => 70],
            // المادة 37: المشروع 1
            ['course_id' => 37, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 37, 'name' => 'نظري', 'percentage' => 70],
            // المادة 38: المشروع 2
            ['course_id' => 38, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 38, 'name' => 'نظري', 'percentage' => 70],
            // المادة 39: المشروع 3
            ['course_id' => 39, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 39, 'name' => 'نظري', 'percentage' => 70],
            // المادة 40: بروتوكولات الاتصال الحاسوبية
            ['course_id' => 40, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 40, 'name' => 'نظري', 'percentage' => 70],
            // المادة 41: خوارزميات البحث الذكية
            ['course_id' => 41, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 41, 'name' => 'نظري', 'percentage' => 70],
            // المادة 42: نظم تشغيل 1
            ['course_id' => 42, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 42, 'name' => 'نظري', 'percentage' => 70],
            // المادة 43: نظم تشغيل 2
            ['course_id' => 43, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 43, 'name' => 'نظري', 'percentage' => 70],
            // المادة 44: البرمجة التفرعية
            ['course_id' => 44, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 44, 'name' => 'نظري', 'percentage' => 70],
            // المادة 45: التسويق
            ['course_id' => 45, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 45, 'name' => 'نظري', 'percentage' => 70],
            // المادة 46: الاقتصاد والإدارة في المؤسسة
            ['course_id' => 46, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 46, 'name' => 'نظري', 'percentage' => 70],
            // المادة 47: إدارة المشاريع
            ['course_id' => 47, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 47, 'name' => 'نظري', 'percentage' => 70],
            // المادة 48: هندسة الرمجيات 1
            ['course_id' => 48, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 48, 'name' => 'نظري', 'percentage' => 70],
            // المادة 49: هندسة الرمجيات 2
            ['course_id' => 49, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 49, 'name' => 'نظري', 'percentage' => 70],
            // المادة 50: هندسة الرمجيات 3
            ['course_id' => 50, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 50, 'name' => 'نظري', 'percentage' => 70],
            // المادة 51: برمجة التطبيقات الشبكية
            ['course_id' => 51, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 51, 'name' => 'نظري', 'percentage' => 70],
            // المادة 52: نظم وساءط متعددة وفائقة
            ['course_id' => 52, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 52, 'name' => 'نظري', 'percentage' => 70],
            // المادة 53: الحقائق الافتراضية
            ['course_id' => 53, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 53, 'name' => 'نظري', 'percentage' => 70],
            // المادة 54: المترجمات 1
            ['course_id' => 54, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 54, 'name' => 'نظري', 'percentage' => 70],
            // المادة 55: مشروع المترجمات
            ['course_id' => 55, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 55, 'name' => 'نظري', 'percentage' => 70],
            // المادة 56: نظم قواعد المعرفة
            ['course_id' => 56, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 56, 'name' => 'نظري', 'percentage' => 70],
            // المادة 57: الشبكات العصبونية
            ['course_id' => 57, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 57, 'name' => 'نظري', 'percentage' => 70],
            // المادة 58: نمذجة ومحاكاة النظم الشبكية
            ['course_id' => 58, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 58, 'name' => 'نظري', 'percentage' => 70],
            // المادة 59: تصميم الشبكات الحاسوبية
            ['course_id' => 59, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 59, 'name' => 'نظري', 'percentage' => 70],
            // المادة 60: أمن نظم المعلومات
            ['course_id' => 60, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 60, 'name' => 'نظري', 'percentage' => 70],
            // المادة 61: النظم والتطبيقات الموزعة
            ['course_id' => 61, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 61, 'name' => 'نظري', 'percentage' => 70],
            // المادة 62: معالجة اللغات الطبيعية
            ['course_id' => 62, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 62, 'name' => 'نظري', 'percentage' => 70],
            // المادة 63: الروبوتية
            ['course_id' => 63, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 63, 'name' => 'نظري', 'percentage' => 70],
            // المادة 64: تطبيقات الانترنت
            ['course_id' => 64, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 64, 'name' => 'نظري', 'percentage' => 70],
            // المادة 65: نظم الزمن الحقيقي
            ['course_id' => 65, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 65, 'name' => 'نظري', 'percentage' => 70],
            // المادة 66: إدارة الشبكات الحاسوبية
            ['course_id' => 66, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 66, 'name' => 'نظري', 'percentage' => 70],
            // المادة 67: أمن الشبكات الحاسوبية
            ['course_id' => 67, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 67, 'name' => 'نظري', 'percentage' => 70],
            // المادة 68: التعلم التلقائي
            ['course_id' => 68, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 68, 'name' => 'نظري', 'percentage' => 70],
            // المادة 69: الرؤيا الحاسوبية
            ['course_id' => 69, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 69, 'name' => 'نظري', 'percentage' => 70],
            // المادة 70: استكشاف المعرفة
            ['course_id' => 70, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 70, 'name' => 'نظري', 'percentage' => 70],
            // المادة 71: نظم البحث عن الملومات
            ['course_id' => 71, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 71, 'name' => 'نظري', 'percentage' => 70],
            // المادة 72: هندسة نظم المعلومات
            ['course_id' => 72, 'name' => 'عملي', 'percentage' => 30],
            ['course_id' => 72, 'name' => 'نظري', 'percentage' => 70],
        ];
        foreach ($staticParts as $part) {
            DB::transaction(function () use ($part) {
                $this->coursePartRepo->create([
                    'course_id' => $part['course_id'],
                    'name' => $part['name'],
                    'percentage' => $part['percentage'],
                ]);
            });
        }
    }
}
