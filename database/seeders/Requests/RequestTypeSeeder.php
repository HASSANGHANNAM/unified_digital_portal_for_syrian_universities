<?php

namespace Database\Seeders\Requests;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\RequestType;
use App\Repositories\Contracts\RequestTypeRepositoryInterface;

class RequestTypeSeeder extends Seeder
{
    public function __construct(
        private RequestTypeRepositoryInterface $requestTypeRepo,
    ) {}

    public function run(): void
    {
        $types = [
            [
                'name' => 'حياة جامعية أو تسلسل دراسي أو بيان وضع',
                'description' => 'طلب الحصول على بيان بالحياة الجامعية أو التسلسل الدراسي أو بيان وضع الطالب',
                'requires_course' => false,
                'teacher_acceptance' => false,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => false,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => false,

            ],
            [
                'name' => 'وثيقة دوام',
                'description' => 'طلب الحصول على وثيقة تثبت دوام الطالب في الجامعة',
                'requires_course' => false,
                'teacher_acceptance' => false,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => false,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => false,
                'doctor_acceptance' => false,
            ],
            [
                'name' => 'كشف علامات',
                'description' => 'طلب الحصول على كشف العلامات الرسمي للمقررات الدراسية',
                'requires_course' => false,
                'teacher_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            [
                'name' => 'إيقاف تسجيل',
                'description' => 'طلب إيقاف تسجيل الطالب بشكل مؤقت أو دائم',
                'requires_course' => false,
                'teacher_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            // [
            //     'name' => 'التحويل المماثل',
            //     'description' => 'طلب التحويل من جامعة إلى جامعة أخرى بنظام المماثلة',
            //     'requires_course' => false,
            //     'teacher_acceptance' => true,
            //     'college_dean_acceptance' => true,
            //     'department_head_acceptance' => true,
            //     'student_stuff_acceptance' => true,
            //     'exams_stuff_acceptance' => true,
            //     'doctor_acceptance' => true,
            // ],
            [
                'name' => 'إشعار تخرج',
                'description' => 'طلب الحصول على إشعار تخرج مؤقت',
                'requires_course' => false,
                'teacher_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            [
                'name' => 'بدل تالف بطاقة جامعة',
                'description' => 'طلب استبدال البطاقة الجامعية التالفة بأخرى جديدة',
                'requires_course' => false,
                'teacher_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            [
                'name' => 'بدل ضائع بطاقة جامعية',
                'description' => 'طلب إصدار بديل عن البطاقة الجامعية المفقودة',
                'requires_course' => false,
                'teacher_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            [
                'name' => 'إعادة عملي',
                'description' => 'طلب إعادة الامتحان العملي لمقرر دراسي',
                'requires_course' => true,
                'teacher_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            [
                'name' => 'اعتراض على علامة',
                'description' => 'طلب اعتراض على علامة مقرر دراسي',
                'requires_course' => true,
                'teacher_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            // [
            //     'name' => 'شهادة تخرج',
            //     'description' => 'طلب الحصول على شهادة التخرج الرسمية',
            //     'requires_course' => false,
            //     'teacher_acceptance' => true,
            //     'college_dean_acceptance' => true,
            //     'department_head_acceptance' => true,
            //     'student_stuff_acceptance' => true,
            //     'exams_stuff_acceptance' => true,
            //     'doctor_acceptance' => true,
            // ],
        ];

        foreach ($types as $type) {
            DB::transaction(function () use ($type) {
                RequestType::firstOrCreate(
                    ['name' => $type['name']],
                    $type
                );
            });
        }
    }
}
