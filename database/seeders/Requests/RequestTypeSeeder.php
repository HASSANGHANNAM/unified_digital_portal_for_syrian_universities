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

            // طلابية
            [
                'name' => 'اعتراض على علامة',
                'description' => 'طلب اعتراض على نتيجة مقرر دراسي',
                'university_director_acceptance' => false,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            [
                'name' => 'طلب حذف مقرر',
                'description' => 'طلب حذف مقرر من جدول الطالب',
                'university_director_acceptance' => false,
                'college_dean_acceptance' => false,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            [
                'name' => 'طلب إضافة مقرر',
                'description' => 'طلب إضافة مقرر إلى جدول الطالب',
                'university_director_acceptance' => false,
                'college_dean_acceptance' => false,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            [
                'name' => 'طلب تأجيل فصل',
                'description' => 'طلب تأجيل فصل دراسي كاملاً',
                'university_director_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => false,
                'doctor_acceptance' => false,
            ],
            [
                'name' => 'طلب استرجاع رسوم',
                'description' => 'طلب استرجاع رسوم مقررات تم حذفها',
                'university_director_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => false,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => false,
                'doctor_acceptance' => false,
            ],
            [
                'name' => 'طلب شهادة تخرج',
                'description' => 'طلب الحصول على شهادة التخرج',
                'university_director_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => false,
            ],
            [
                'name' => 'طلب معادلة مقرر',
                'description' => 'طلب معادلة مقرر دراسي من جامعة سابقة',
                'university_director_acceptance' => false,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            [
                'name' => 'طلب تحويل مسار',
                'description' => 'طلب التحويل من تخصص إلى آخر',
                'university_director_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => false,
                'doctor_acceptance' => false,
            ],
            [
                'name' => 'طلب كشف علامات',
                'description' => 'طلب الحصول على كشف علامات رسمي',
                'university_director_acceptance' => false,
                'college_dean_acceptance' => false,
                'department_head_acceptance' => false,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => false,
            ],
            [
                'name' => 'طلب اعادة اختبار',
                'description' => 'طلب إعادة اختبار لدور ثاني',
                'university_director_acceptance' => false,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => true,
                'doctor_acceptance' => true,
            ],
            [
                'name' => 'طلب انسحاب من جامعة',
                'description' => 'طلب الانسحاب النهائي من الجامعة',
                'university_director_acceptance' => true,
                'college_dean_acceptance' => true,
                'department_head_acceptance' => true,
                'student_stuff_acceptance' => true,
                'exams_stuff_acceptance' => false,
                'doctor_acceptance' => false,
            ],

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
