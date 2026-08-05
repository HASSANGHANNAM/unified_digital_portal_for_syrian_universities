<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use App\Models\StudyPlanCourse;
use App\Models\Department;
use App\Models\Course;

class StudyPlanCourseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. جلب جميع الأقسام وتجميعها حسب الاسم
        $departments = Department::all();
        $departmentsByName = $departments->groupBy('name');

        // 2. الخطة الدراسية (نفس المصفوفة السابقة)
        $studyPlan = [
            // ========== السنة الأولى - الفصل الأول ==========
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'اللغة الانكليزية 1', 'year' => 1, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'البرمجة 1', 'year' => 1, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'مبادئ عمل الحاسوب', 'year' => 1, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'الفيزياء', 'year' => 1, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'اللغة العربية', 'year' => 1, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'تحليل 1', 'year' => 1, 'semester' => 1],

            // ========== السنة الأولى - الفصل الثاني ==========
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'البرمجة 2', 'year' => 1, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'الجبر العام', 'year' => 1, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'اللغة الانكليزية 2', 'year' => 1, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'تحليل 2', 'year' => 1, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'الدارات الكهربائية والالكترونية', 'year' => 1, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'الجبر الخطي', 'year' => 1, 'semester' => 2],

            // ========== السنة الثانية - الفصل الأول ==========
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'الخوارزميات وبنى المعطيات 2', 'year' => 2, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'البرمجة 3', 'year' => 2, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'الدارات المنطقية', 'year' => 2, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'الاتصالات الرقمية', 'year' => 2, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'احتمالات وإحصاء', 'year' => 2, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'مهارات تواصل', 'year' => 2, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'تحليل 3', 'year' => 2, 'semester' => 1],

            // ========== السنة الثانية - الفصل الثاني ==========
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'التحليل العددي', 'year' => 2, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'اللغة الانكليزية 3', 'year' => 2, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'اللغة الانكليزية 4', 'year' => 2, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'الخوارزميات وبنى المعطيات 1', 'year' => 2, 'semester' => 2],

            // ========== السنة الثالثة - الفصل الأول ==========
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'لغات البرمجة', 'year' => 3, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'بنيان الحواسيب 2', 'year' => 3, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'اللغات الصورية', 'year' => 3, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'أساسيات الشبكات المعلوماتية', 'year' => 3, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'بيانيات حاسوبية', 'year' => 3, 'semester' => 1],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'حسابات علمية', 'year' => 3, 'semester' => 1],

            // ========== السنة الثالثة - الفصل الثاني ==========
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'بحوث العمليات', 'year' => 3, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'مشروع 1', 'year' => 3, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'قواعد المعطيات 1', 'year' => 3, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'مبادئ الذكاء الصنعي', 'year' => 3, 'semester' => 2],
            ['department_name' => 'العلوم الأساسية', 'course_name' => 'بنيان الحواسيب 1', 'year' => 3, 'semester' => 2],

            // ========== السنة الرابعة - هندسة البرمجيات ==========
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'نظم تشغيل 1', 'year' => 4, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'المترجمات', 'year' => 4, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'الاقتصاد والإدارة في المؤسسة', 'year' => 4, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'هندسة البرمجيات 1', 'year' => 4, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'قواعد المعطيات 2', 'year' => 4, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'نظم وسائط متعددة وفائقة', 'year' => 4, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'هندسة البرمجيات 2', 'year' => 4, 'semester' => 2],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'مشروع 2', 'year' => 4, 'semester' => 2],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'خوارزميات البحث الذكية', 'year' => 4, 'semester' => 2],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'مشروع مترجمات', 'year' => 4, 'semester' => 2],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'البرمجة التفرعية', 'year' => 4, 'semester' => 2],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'التسويق', 'year' => 4, 'semester' => 2],

            // ========== السنة الرابعة - النظم والشبكات ==========
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'نظم تشغيل 1', 'year' => 4, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'المترجمات', 'year' => 4, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'الاقتصاد والإدارة في المؤسسة', 'year' => 4, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'هندسة البرمجيات 1', 'year' => 4, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'برمجة التطبيقات الشبكية', 'year' => 4, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'نظم وسائط متعددة وفائقة', 'year' => 4, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'بروتوكولات الاتصال الحاسوبية', 'year' => 4, 'semester' => 2],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'مشروع 2', 'year' => 4, 'semester' => 2],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'خوارزميات البحث الذكية', 'year' => 4, 'semester' => 2],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'البرمجة التفرعية', 'year' => 4, 'semester' => 2],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'التسويق', 'year' => 4, 'semester' => 2],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'نظم تشغيل 2', 'year' => 4, 'semester' => 2],

            // ========== السنة الرابعة - الذكاء الصنعي ==========
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'نظم تشغيل 1', 'year' => 4, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'المترجمات', 'year' => 4, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'الاقتصاد والإدارة في المؤسسة', 'year' => 4, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'هندسة البرمجيات 1', 'year' => 4, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'الشبكات العصبونية', 'year' => 4, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'نظم وسائط متعددة وفائقة', 'year' => 4, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'الحقائق الافتراضية', 'year' => 4, 'semester' => 2],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'مشروع 2', 'year' => 4, 'semester' => 2],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'خوارزميات البحث الذكية', 'year' => 4, 'semester' => 2],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'البرمجة التفرعية', 'year' => 4, 'semester' => 2],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'التسويق', 'year' => 4, 'semester' => 2],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'نظم قواعد المعرفة', 'year' => 4, 'semester' => 2],

            // ========== السنة الخامسة - هندسة البرمجيات ==========
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'أمن نظم المعلومات', 'year' => 5, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'تطبيقات الانترنت', 'year' => 5, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'نظم البحث عن المعلومات', 'year' => 5, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'قواعد معطيات متقدمة', 'year' => 5, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'هندسة نظم المعلومات', 'year' => 5, 'semester' => 1],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'النظم والتطبيقات الموزعة', 'year' => 5, 'semester' => 2],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'إدارة المشاريع', 'year' => 5, 'semester' => 2],
            ['department_name' => 'هندسة البرمجيات ونظم المعلومات', 'course_name' => 'هندسة البرمجيات 3', 'year' => 5, 'semester' => 2],

            // ========== السنة الخامسة - النظم والشبكات ==========
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'أمن نظم المعلومات', 'year' => 5, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'النظم والتطبيقات الموزعة', 'year' => 5, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'نظم الزمن الحقيقي', 'year' => 5, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'إدارة الشبكات الحاسوبية', 'year' => 5, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'أمن الشبكات الحاسوبية', 'year' => 5, 'semester' => 1],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'نمذجة ومحاكاة النظم الشبكية', 'year' => 5, 'semester' => 2],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'إدارة المشاريع', 'year' => 5, 'semester' => 2],
            ['department_name' => 'النظم والشبكات الحاسوبية', 'course_name' => 'تصميم الشبكات الحاسوبية', 'year' => 5, 'semester' => 2],

            // ========== السنة الخامسة - الذكاء الصنعي ==========
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'أمن نظم المعلومات', 'year' => 5, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'الروبوتية', 'year' => 5, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'التعلم التلقائي', 'year' => 5, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'الرؤيا الحاسوبية', 'year' => 5, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'استكشاف المعرفة', 'year' => 5, 'semester' => 1],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'إدارة المشاريع', 'year' => 5, 'semester' => 2],
            ['department_name' => 'الذكاء الاصطناعي', 'course_name' => 'معالجة اللغات الطبيعية', 'year' => 5, 'semester' => 2],
        ];

        // ============================================================
        // 3. إدراج الخطة لكل قسم يحمل نفس الاسم
        // ============================================================
        foreach ($studyPlan as $item) {
            $departmentName = $item['department_name'];
            $courseName = $item['course_name'];
            $year = $item['year'];
            $semester = $item['semester'];

            // الحصول على جميع الأقسام التي تحمل هذا الاسم
            $targetDepartments = $departmentsByName[$departmentName] ?? collect();

            if ($targetDepartments->isEmpty()) {
                $this->command->warn("⚠️  القسم '{$departmentName}' غير موجود. تخطي المادة: {$courseName}");
                continue;
            }

            foreach ($targetDepartments as $department) {
                // البحث عن المادة باستخدام العلاقة مع universal_courses
                $course = Course::where('department_id', $department->id)
                    ->whereHas('universalCourse', function ($query) use ($courseName) {
                        $query->where('name', $courseName);
                    })
                    ->first();

                if (!$course) {
                    $this->command->warn("⚠️  المادة '{$courseName}' غير موجودة في القسم '{$departmentName}' (dept_id: {$department->id}). تخطي.");
                    continue;
                }

                // إدراج الخطة (بدون college_id)
                StudyPlanCourse::firstOrCreate(
                    [
                        'course_id'     => $course->id,
                        'department_id' => $department->id,
                        'year'          => $year,
                        'semester'      => $semester,
                    ]
                );
            }
        }

        $this->command->info('✅ تم إدراج الخطة الدراسية لجميع الأقسام بنجاح!');
    }
}
