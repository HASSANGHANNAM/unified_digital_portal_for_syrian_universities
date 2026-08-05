<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Course;
use App\Models\UniversalCourse;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. جلب الأقسام مجمعة حسب الاسم، مع ترتيبها حسب college_id ثم id
        // ============================================================
        $departmentsGrouped = Department::orderBy('college_id')
            ->orderBy('id')
            ->get()
            ->groupBy('name');

        // ============================================================
        // 2. بيانات المواد (مع اسم القسم والكود والساعات فقط، بدون سنة/فصل)
        // ============================================================
        $coursesData = [
            // ========== السنة الأولى - الفصل الأول ==========
            ['name' => 'اللغة الانكليزية 1', 'department_name' => 'العلوم الأساسية', 'code' => 'CS101', 'credits' => 3],
            ['name' => 'البرمجة 1', 'department_name' => 'العلوم الأساسية', 'code' => 'CS201', 'credits' => 4],
            ['name' => 'مبادئ عمل الحاسوب', 'department_name' => 'العلوم الأساسية', 'code' => 'CS121', 'credits' => 3],
            ['name' => 'الفيزياء', 'department_name' => 'العلوم الأساسية', 'code' => 'CS131', 'credits' => 3],
            ['name' => 'اللغة العربية', 'department_name' => 'العلوم الأساسية', 'code' => 'CS141', 'credits' => 2],
            ['name' => 'تحليل 1', 'department_name' => 'العلوم الأساسية', 'code' => 'CS113', 'credits' => 3],

            // ========== السنة الأولى - الفصل الثاني ==========
            ['name' => 'البرمجة 2', 'department_name' => 'العلوم الأساسية', 'code' => 'CS202', 'credits' => 4],
            ['name' => 'الجبر العام', 'department_name' => 'العلوم الأساسية', 'code' => 'CS111', 'credits' => 3],
            ['name' => 'اللغة الانكليزية 2', 'department_name' => 'العلوم الأساسية', 'code' => 'CS102', 'credits' => 3],
            ['name' => 'تحليل 2', 'department_name' => 'العلوم الأساسية', 'code' => 'CS114', 'credits' => 3],
            ['name' => 'الدارات الكهربائية والالكترونية', 'department_name' => 'العلوم الأساسية', 'code' => 'CS132', 'credits' => 3],
            ['name' => 'الجبر الخطي', 'department_name' => 'العلوم الأساسية', 'code' => 'CS112', 'credits' => 3],

            // ========== السنة الثانية - الفصل الأول ==========
            ['name' => 'الخوارزميات وبنى المعطيات 2', 'department_name' => 'العلوم الأساسية', 'code' => 'CS242', 'credits' => 4],
            ['name' => 'البرمجة 3', 'department_name' => 'العلوم الأساسية', 'code' => 'CS203', 'credits' => 4],
            ['name' => 'الدارات المنطقية', 'department_name' => 'العلوم الأساسية', 'code' => 'CS133', 'credits' => 3],
            ['name' => 'الاتصالات الرقمية', 'department_name' => 'العلوم الأساسية', 'code' => 'CS251', 'credits' => 3],
            ['name' => 'احتمالات وإحصاء', 'department_name' => 'العلوم الأساسية', 'code' => 'CS117', 'credits' => 3],
            ['name' => 'مهارات تواصل', 'department_name' => 'العلوم الأساسية', 'code' => 'CS142', 'credits' => 2],
            ['name' => 'تحليل 3', 'department_name' => 'العلوم الأساسية', 'code' => 'CS115', 'credits' => 3],

            // ========== السنة الثانية - الفصل الثاني ==========
            ['name' => 'التحليل العددي', 'department_name' => 'العلوم الأساسية', 'code' => 'CS116', 'credits' => 3],
            ['name' => 'اللغة الانكليزية 3', 'department_name' => 'العلوم الأساسية', 'code' => 'CS103', 'credits' => 3],
            ['name' => 'اللغة الانكليزية 4', 'department_name' => 'العلوم الأساسية', 'code' => 'CS104', 'credits' => 3],
            ['name' => 'الخوارزميات وبنى المعطيات 1', 'department_name' => 'العلوم الأساسية', 'code' => 'CS241', 'credits' => 4],

            // ========== السنة الثالثة - الفصل الأول ==========
            ['name' => 'لغات البرمجة', 'department_name' => 'العلوم الأساسية', 'code' => 'CS261', 'credits' => 3],
            ['name' => 'بنيان الحواسيب 2', 'department_name' => 'العلوم الأساسية', 'code' => 'CS123', 'credits' => 3],
            ['name' => 'اللغات الصورية', 'department_name' => 'العلوم الأساسية', 'code' => 'CS262', 'credits' => 3],
            ['name' => 'أساسيات الشبكات المعلوماتية', 'department_name' => 'العلوم الأساسية', 'code' => 'CS281', 'credits' => 3],
            ['name' => 'بيانيات حاسوبية', 'department_name' => 'العلوم الأساسية', 'code' => 'CS263', 'credits' => 3],
            ['name' => 'حسابات علمية', 'department_name' => 'العلوم الأساسية', 'code' => 'CS264', 'credits' => 3],

            // ========== السنة الثالثة - الفصل الثاني ==========
            ['name' => 'بحوث العمليات', 'department_name' => 'العلوم الأساسية', 'code' => 'CS252', 'credits' => 3],
            ['name' => 'مشروع 1', 'department_name' => 'العلوم الأساسية', 'code' => 'CS391', 'credits' => 3],
            ['name' => 'قواعد المعطيات 1', 'department_name' => 'العلوم الأساسية', 'code' => 'CS231', 'credits' => 4],
            ['name' => 'مبادئ الذكاء الصنعي', 'department_name' => 'العلوم الأساسية', 'code' => 'CS271', 'credits' => 3],
            ['name' => 'بنيان الحواسيب 1', 'department_name' => 'العلوم الأساسية', 'code' => 'CS122', 'credits' => 4],

            // ========== السنة الرابعة - هندسة البرمجيات ==========
            ['name' => 'نظم تشغيل 1', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS341', 'credits' => 4],
            ['name' => 'المترجمات', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS361', 'credits' => 3],
            ['name' => 'الاقتصاد والإدارة في المؤسسة', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS144', 'credits' => 2],
            ['name' => 'هندسة البرمجيات 1', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'SE351', 'credits' => 4],
            ['name' => 'قواعد المعطيات 2', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS232', 'credits' => 3],
            ['name' => 'نظم وسائط متعددة وفائقة', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS382', 'credits' => 3],
            ['name' => 'هندسة البرمجيات 2', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'SE352', 'credits' => 3],
            ['name' => 'مشروع 2', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS392', 'credits' => 3],
            ['name' => 'خوارزميات البحث الذكية', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS272', 'credits' => 3],
            ['name' => 'مشروع مترجمات', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS362', 'credits' => 3],
            ['name' => 'البرمجة التفرعية', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS343', 'credits' => 3],
            ['name' => 'التسويق', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS143', 'credits' => 2],

            // ========== السنة الرابعة - النظم والشبكات ==========
            ['name' => 'نظم تشغيل 1', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS341', 'credits' => 4],
            ['name' => 'المترجمات', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS361', 'credits' => 3],
            ['name' => 'الاقتصاد والإدارة في المؤسسة', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS144', 'credits' => 2],
            ['name' => 'هندسة البرمجيات 1', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'SE351', 'credits' => 4],
            ['name' => 'برمجة التطبيقات الشبكية', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS381', 'credits' => 3],
            ['name' => 'نظم وسائط متعددة وفائقة', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS382', 'credits' => 3],
            ['name' => 'بروتوكولات الاتصال الحاسوبية', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS282', 'credits' => 3],
            ['name' => 'مشروع 2', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS392', 'credits' => 3],
            ['name' => 'خوارزميات البحث الذكية', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS272', 'credits' => 3],
            ['name' => 'البرمجة التفرعية', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS343', 'credits' => 3],
            ['name' => 'التسويق', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS143', 'credits' => 2],
            ['name' => 'نظم تشغيل 2', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS342', 'credits' => 3],

            // ========== السنة الرابعة - الذكاء الصنعي ==========
            ['name' => 'نظم تشغيل 1', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS341', 'credits' => 4],
            ['name' => 'المترجمات', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS361', 'credits' => 3],
            ['name' => 'الاقتصاد والإدارة في المؤسسة', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS144', 'credits' => 2],
            ['name' => 'هندسة البرمجيات 1', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'SE351', 'credits' => 4],
            ['name' => 'الشبكات العصبونية', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS373', 'credits' => 3],
            ['name' => 'نظم وسائط متعددة وفائقة', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS382', 'credits' => 3],
            ['name' => 'الحقائق الافتراضية', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS383', 'credits' => 3],
            ['name' => 'مشروع 2', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS392', 'credits' => 3],
            ['name' => 'خوارزميات البحث الذكية', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS272', 'credits' => 3],
            ['name' => 'البرمجة التفرعية', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS343', 'credits' => 3],
            ['name' => 'التسويق', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS143', 'credits' => 2],
            ['name' => 'نظم قواعد المعرفة', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS333', 'credits' => 3],

            // ========== السنة الخامسة - هندسة البرمجيات ==========
            ['name' => 'أمن نظم المعلومات', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS471', 'credits' => 3],
            ['name' => 'تطبيقات الانترنت', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS385', 'credits' => 3],
            ['name' => 'نظم البحث عن المعلومات', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS379', 'credits' => 3],
            ['name' => 'قواعد معطيات متقدمة', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS331', 'credits' => 3],
            ['name' => 'هندسة نظم المعلومات', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'SE451', 'credits' => 3],
            ['name' => 'النظم والتطبيقات الموزعة', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS442', 'credits' => 3],
            ['name' => 'إدارة المشاريع', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'CS145', 'credits' => 2],
            ['name' => 'هندسة البرمجيات 3', 'department_name' => 'هندسة البرمجيات ونظم المعلومات', 'code' => 'SE353', 'credits' => 3],

            // ========== السنة الخامسة - النظم والشبكات ==========
            ['name' => 'أمن نظم المعلومات', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS471', 'credits' => 3],
            ['name' => 'النظم والتطبيقات الموزعة', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS442', 'credits' => 3],
            ['name' => 'نظم الزمن الحقيقي', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS443', 'credits' => 3],
            ['name' => 'إدارة الشبكات الحاسوبية', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS285', 'credits' => 3],
            ['name' => 'أمن الشبكات الحاسوبية', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS472', 'credits' => 3],
            ['name' => 'نمذجة ومحاكاة النظم الشبكية', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS283', 'credits' => 3],
            ['name' => 'إدارة المشاريع', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS145', 'credits' => 2],
            ['name' => 'تصميم الشبكات الحاسوبية', 'department_name' => 'النظم والشبكات الحاسوبية', 'code' => 'CS284', 'credits' => 3],

            // ========== السنة الخامسة - الذكاء الصنعي ==========
            ['name' => 'أمن نظم المعلومات', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS471', 'credits' => 3],
            ['name' => 'الروبوتية', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS375', 'credits' => 3],
            ['name' => 'التعلم التلقائي', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS376', 'credits' => 3],
            ['name' => 'الرؤيا الحاسوبية', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS377', 'credits' => 3],
            ['name' => 'استكشاف المعرفة', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS378', 'credits' => 3],
            ['name' => 'إدارة المشاريع', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS145', 'credits' => 2],
            ['name' => 'معالجة اللغات الطبيعية', 'department_name' => 'الذكاء الاصطناعي', 'code' => 'CS374', 'credits' => 3],
        ];

        // ============================================================
        // 3. إدراج المواد (لكل قسم بنفس الاسم، مع ترتيب ID حسب college_id)
        // ============================================================

        // متغير لتتبع الـ ID الحالي
        $currentId = 1;

        foreach ($coursesData as $courseData) {
            // جلب جميع الأقسام التي تحمل هذا الاسم (مرتبة حسب college_id)
            $departments = $departmentsGrouped[$courseData['department_name']] ?? collect();

            if ($departments->isEmpty()) {
                $this->command->warn("⚠️  القسم '{$courseData['department_name']}' غير موجود. تخطي المادة: {$courseData['name']}");
                continue;
            }

            // إضافة المادة لكل قسم موجود بهذا الاسم
            foreach ($departments as $department) {
                // 1. إنشاء UniversalCourse (المادة العالمية)
                $universalCourse = UniversalCourse::firstOrCreate(
                    ['name' => $courseData['name']],
                    ['image' => null]
                );

                // 2. إنشاء Course مرتبط بالقسم مع تعيين ID يدوياً (بدون year/semester)
                $course = Course::firstOrCreate(
                    [
                        'code' => $courseData['code'],
                        'department_id' => $department->id,
                    ],
                    [
                        'id' => $currentId,
                        'credits' => $courseData['credits'],
                        'universal_course_id' => $universalCourse->id,
                        'college_id' => $department->college_id,
                        'department_id' => $department->id,
                    ]
                );

                // إذا تم إنشاء سجل جديد (وليس موجوداً مسبقاً)، نزيد الـ ID
                if ($course->wasRecentlyCreated) {
                    $currentId++;
                }
            }
        }

        // ============================================================
        // 4. إعادة تعيين التسلسل التلقائي إلى آخر قيمة
        // ============================================================
        $lastId = Course::max('id') ?? 1;
        DB::statement("ALTER TABLE courses AUTO_INCREMENT = " . ($lastId + 1));

        $this->command->info("✅ تم إضافة جميع المواد للأقسام بنجاح! عدد المواد: " . ($currentId - 1));
    }
}
