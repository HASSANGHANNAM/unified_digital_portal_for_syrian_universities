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
        // جلب جميع الأقسام
        $departments = Department::whereIn('name', [
            'هندسة البرمجيات',
            'الذكاء الاصطناعي',
            'الشبكات',
            'الأمن السيبراني',
            'الجراحة العامة',
            'طب الأطفال',
            'طب النساء والتوليد',
            'الفيزياء',
            'الكيمياء',
            'الأحياء',
            
        ])->get()->keyBy('name');

        // تعريف المقررات لكل قسم
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

        // إنشاء المقررات لكل قسم
        foreach ($coursesByDepartment as $departmentName => $courses) {
            $department = $departments[$departmentName];

            foreach ($courses as $courseData) {
                DB::transaction(function () use ($courseData, $department) {
                    // البحث عن UniversalCourse
                    $universalCourse = UniversalCourse::where('name', $courseData['course_name'])->first();

                    // إذا لم يوجد UniversalCourse، قم بإنشاؤه تلقائياً
                    if (!$universalCourse) {
                        $universalCourse = UniversalCourse::create([
                            'name' => $courseData['course_name'],
                        ]);
                    }

                    $this->courseRepo->create([
                        'code' => $courseData['code'],
                        'credits' => $courseData['credits'],
                        'universal_course_id' => $universalCourse->id,
                        'college_id' => $department->college_id,
                        'department_id' => $department->id,
                    ]);
                });
            }
        }

        // عرض ملخص
        // $this->command->info('تم إنشاء المقررات بنجاح!');
        // foreach ($departments as $department) {
        //     $count = DB::table('courses')->where('department_id', $department->id)->count();
        //     // $this->command->line("- {$department->name}: {$count} مقرر");
        // }
    }
}
