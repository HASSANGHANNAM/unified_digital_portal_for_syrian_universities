<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Models\Department;
use App\Models\UniversalCourse;

class CourseSeeder extends Seeder
{
    public function __construct(
        private CourseRepositoryInterface $courseRepo,
    ) {}

    public function run(): void
    {
        // جلب جميع الأقسام (بدون فلتر) لتشمل الأقسام الجديدة
        $departments = Department::all()->keyBy('name');

        // تعريف المقررات لكل قسم (الموجودة مسبقاً)
        $coursesByDepartment = [
            'هندسة البرمجيات' => [
                ['course_name' => 'قواعد البيانات', 'code' => 'SE301', 'credits' => 4],
                ['course_name' => 'هندسة المتطلبات', 'code' => 'SE302', 'credits' => 3],
                ['course_name' => 'تصميم واجهات المستخدم', 'code' => 'SE303', 'credits' => 3],
                ['course_name' => 'إدارة المشاريع البرمجية', 'code' => 'SE401', 'credits' => 3],
            ],
            'الذكاء الاصطناعي' => [
                ['course_name' => 'الذكاء الاصطناعي', 'code' => 'AI401', 'credits' => 3],
                ['course_name' => 'تعلم الآلة', 'code' => 'AI402', 'credits' => 4],
                ['course_name' => 'معالجة اللغة الطبيعية', 'code' => 'AI403', 'credits' => 3],
                ['course_name' => 'رؤية الحاسوب', 'code' => 'AI404', 'credits' => 3],
            ],
            'الشبكات' => [
                ['course_name' => 'شبكات الحاسوب', 'code' => 'NET301', 'credits' => 4],
                ['course_name' => 'بروتوكولات الشبكات', 'code' => 'NET302', 'credits' => 3],
                ['course_name' => 'الشبكات اللاسلكية', 'code' => 'NET303', 'credits' => 3],
                ['course_name' => 'إدارة الشبكات', 'code' => 'NET401', 'credits' => 3],
            ],
            'الأمن السيبراني' => [
                ['course_name' => 'أمن الشبكات', 'code' => 'SEC301', 'credits' => 3],
                ['course_name' => 'التشفير', 'code' => 'SEC302', 'credits' => 4],
                ['course_name' => 'أمن التطبيقات', 'code' => 'SEC303', 'credits' => 3],
                ['course_name' => 'الاختراق الأخلاقي', 'code' => 'SEC401', 'credits' => 3],
            ],
            'الجراحة العامة' => [
                ['course_name' => 'أساسيات الجراحة', 'code' => 'SUR301', 'credits' => 4],
                ['course_name' => 'الجراحة العامة', 'code' => 'SUR302', 'credits' => 4],
                ['course_name' => 'جراحة الطوارئ', 'code' => 'SUR303', 'credits' => 3],
                ['course_name' => 'تقنيات الجراحة الحديثة', 'code' => 'SUR401', 'credits' => 3],
            ],
            'طب الأطفال' => [
                ['course_name' => 'أمراض الأطفال', 'code' => 'PED301', 'credits' => 4],
                ['course_name' => 'تغذية الأطفال', 'code' => 'PED302', 'credits' => 3],
                ['course_name' => 'صحة الطفل', 'code' => 'PED303', 'credits' => 3],
                ['course_name' => 'أدوية الأطفال', 'code' => 'PED401', 'credits' => 3],
            ],
            'طب النساء والتوليد' => [
                ['course_name' => 'صحة المرأة', 'code' => 'OBS301', 'credits' => 3],
                ['course_name' => 'التوليد', 'code' => 'OBS302', 'credits' => 4],
                ['course_name' => 'أمراض النساء', 'code' => 'OBS303', 'credits' => 4],
                ['course_name' => 'تنظيم الأسرة', 'code' => 'OBS401', 'credits' => 3],
            ],
            'الفيزياء' => [
                ['course_name' => 'الفيزياء العامة', 'code' => 'PHY201', 'credits' => 4],
                ['course_name' => 'الفيزياء الحديثة', 'code' => 'PHY301', 'credits' => 3],
                ['course_name' => 'الكهرومغناطيسية', 'code' => 'PHY302', 'credits' => 4],
                ['course_name' => 'فيزياء الكم', 'code' => 'PHY401', 'credits' => 3],
            ],
            'الكيمياء' => [
                ['course_name' => 'الكيمياء العامة', 'code' => 'CHM201', 'credits' => 4],
                ['course_name' => 'الكيمياء العضوية', 'code' => 'CHM301', 'credits' => 4],
                ['course_name' => 'الكيمياء التحليلية', 'code' => 'CHM302', 'credits' => 3],
                ['course_name' => 'الكيمياء الحيوية', 'code' => 'CHM401', 'credits' => 3],
            ],
            'الأحياء' => [
                ['course_name' => 'الأحياء العامة', 'code' => 'BIO201', 'credits' => 4],
                ['course_name' => 'الوراثة', 'code' => 'BIO301', 'credits' => 3],
                ['course_name' => 'علم الخلية', 'code' => 'BIO302', 'credits' => 3],
                ['course_name' => 'علم البيئة', 'code' => 'BIO401', 'credits' => 3],
            ],





        ];

        // -------------------- إضافة المقررات الجديدة من dummyData.ts --------------------
        // أقسام جديدة قد لا تكون موجودة في المصفوفة أعلاه
        $coursesByDepartment['رياضيات'] = [
            ['course_name' => 'التحليل الرياضي 1', 'code' => 'MATH101', 'credits' => 3],
            ['course_name' => 'نظرية الأعداد', 'code' => 'MATH301', 'credits' => 3],
        ];

        $coursesByDepartment['إدارة أعمال'] = [
            ['course_name' => 'مبادئ الاقتصاد', 'code' => 'ECO101', 'credits' => 3],
        ];

        $coursesByDepartment['تاريخ'] = [
            ['course_name' => 'تاريخ الحضارات', 'code' => 'HIST101', 'credits' => 2],
        ];

        $coursesByDepartment['محاسبة'] = [
            ['course_name' => 'محاسبة مالية', 'code' => 'ACC201', 'credits' => 4],
        ];

        // إضافة مقررات إضافية للأقسام الموجودة (دمج مع القديم)
        // تصحيح: استخدام 'هندسة البرمجيات' بدلاً من 'هندسة برمجيات'
        if (isset($coursesByDepartment['هندسة البرمجيات'])) {
            $coursesByDepartment['هندسة البرمجيات'] = array_merge($coursesByDepartment['هندسة البرمجيات'], [
                ['course_name' => 'مقدمة في البرمجة', 'code' => 'CS101', 'credits' => 3],
                ['course_name' => 'هياكل البيانات', 'code' => 'CS201', 'credits' => 4],
            ]);
        } else {
            $coursesByDepartment['هندسة البرمجيات'] = [
                ['course_name' => 'مقدمة في البرمجة', 'code' => 'CS101', 'credits' => 3],
                ['course_name' => 'هياكل البيانات', 'code' => 'CS201', 'credits' => 4],
            ];
        }

        // تصحيح: استخدام 'الفيزياء' بدلاً من 'فيزياء'
        if (isset($coursesByDepartment['الفيزياء'])) {
            $coursesByDepartment['الفيزياء'] = array_merge($coursesByDepartment['الفيزياء'], [
                ['course_name' => 'الفيزياء العامة', 'code' => 'PHY101', 'credits' => 4],
            ]);
        } else {
            $coursesByDepartment['الفيزياء'] = [
                ['course_name' => 'الفيزياء العامة', 'code' => 'PHY101', 'credits' => 4],
            ];
        }

        if (isset($coursesByDepartment['الجراحة العامة'])) {
            $coursesByDepartment['الجراحة العامة'] = array_merge($coursesByDepartment['الجراحة العامة'], [
                ['course_name' => 'علم التشريح', 'code' => 'MED201', 'credits' => 5],
            ]);
        } else {
            $coursesByDepartment['الجراحة العامة'] = [
                ['course_name' => 'علم التشريح', 'code' => 'MED201', 'credits' => 5],
            ];
        }

        // -------------------- إنشاء المقررات لكل قسم --------------------
        foreach ($coursesByDepartment as $departmentName => $courses) {
            // التأكد من وجود القسم في قاعدة البيانات
            if (!isset($departments[$departmentName])) {
                continue;
            }

            $department = $departments[$departmentName];

            foreach ($courses as $courseData) {
                DB::transaction(function () use ($courseData, $department) {
                    // البحث عن UniversalCourse أو إنشاؤه
                    $universalCourse = UniversalCourse::firstOrCreate(
                        ['name' => $courseData['course_name']],
                        ['image' => null]
                    );

                    // التحقق من عدم وجود المقرر بنفس الكود والقسم
                    $exists = \App\Models\Course::where('code', $courseData['code'])
                        ->where('department_id', $department->id)
                        ->exists();

                    if (!$exists) {
                        $this->courseRepo->create([
                            'code' => $courseData['code'],
                            'credits' => $courseData['credits'],
                            'universal_course_id' => $universalCourse->id,
                            'college_id' => $department->college_id,
                            'department_id' => $department->id,
                        ]);
                    }
                });
            }
        }
    }
}
