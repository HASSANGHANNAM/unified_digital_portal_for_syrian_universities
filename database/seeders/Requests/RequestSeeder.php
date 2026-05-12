<?php

namespace Database\Seeders\Requests;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StudentRequestRepositoryInterface;
use App\Models\RequestType;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Course;

class RequestSeeder extends Seeder
{
    public function __construct(
        private StudentRequestRepositoryInterface $requestRepo,
    ) {}

    public function run(): void
    {
        $students = Student::all();
        $staffMembers = Staff::all();
        $courses = Course::all();


        $requestTemplates = [
            [
                'type_name' => 'اعتراض على علامة',
                'reason' => 'أعتقد أن هناك خطأ في جمع العلامات النهائية للمقرر.',
                'decision_reason' => 'تمت مراجعة العلامات وتعديل النتيجة.',
                'status' => 'approved',
                'days_to_decision' => 3,
            ],
            [
                'type_name' => 'طلب حذف مقرر',
                'reason' => 'المقرر يسبب صعوبة كبيرة ويتعارض مع مواعيد عملي.',
                'decision_reason' => 'تم قبول الطلب وحذف المقرر من جدول الطالب.',
                'status' => 'approved',
                'days_to_decision' => 2,
            ],
            [
                'type_name' => 'طلب إضافة مقرر',
                'reason' => 'أريد إضافة مقرر اختياري لتكملة عدد الساعات المطلوبة.',
                'decision_reason' => 'تمت الموافقة على إضافة المقرر بعد التأكد من توفر المقاعد.',
                'status' => 'approved',
                'days_to_decision' => 2,
            ],
            [
                'type_name' => 'طلب تأجيل فصل دراسي',
                'reason' => 'ظروف صحية تمنعني من متابعة الدراسة هذا الفصل.',
                'decision_reason' => 'تم قبول طلب التأجيل بناءً على التقرير الطبي.',
                'status' => 'approved',
                'days_to_decision' => 5,
            ],
            [
                'type_name' => 'طلب استرجاع رسوم',
                'reason' => 'تم حذف المقرر ولم أعد بحاجة للرسوم المدفوعة.',
                'decision_reason' => 'تمت الموافقة على استرجاع 100% من الرسوم.',
                'status' => 'approved',
                'days_to_decision' => 7,
            ],
            [
                'type_name' => 'طلب شهادة تخرج',
                'reason' => 'أنهيت متطلبات التخرج وأحتاج الشهادة للتقديم على وظيفة.',
                'decision_reason' => 'تم تجهيز شهادة التخرج وجاهزة للاستلام.',
                'status' => 'approved',
                'days_to_decision' => 10,
            ],
            [
                'type_name' => 'طلب معادلة مقرر',
                'reason' => 'درست مقرر مشابه في جامعة سابقة وأريد معادلته.',
                'decision_reason' => 'تمت معادلة المقرر بعد مطابقة المحتوى الدراسي.',
                'status' => 'approved',
                'days_to_decision' => 6,
            ],
            [
                'type_name' => 'طلب تحويل مسار',
                'reason' => 'أرغب في التحويل من هندسة البرمجيات إلى الذكاء الاصطناعي.',
                'decision_reason' => 'تمت الموافقة على التحويل بعد استيفاء المعدل المطلوب.',
                'status' => 'pending',
                'days_to_decision' => null,
            ],
            [
                'type_name' => 'طلب اعادة اختبار',
                'reason' => 'تغيب عن الاختبار النهائي بسبب ظرف طارئ.',
                'decision_reason' => 'تم تحديد موعد للاختبار البديل.',
                'status' => 'approved',
                'days_to_decision' => 4,
            ],
            [
                'type_name' => 'طلب تدريب عملي',
                'reason' => 'أحتاج إلى موافقة على التدريب في شركة معينة.',
                'decision_reason' => 'تمت الموافقة على موقع التدريب.',
                'status' => 'approved',
                'days_to_decision' => 5,
            ],
            [
                'type_name' => 'طلب منحة دراسية',
                'reason' => 'لدي تفوق دراسي وأحتاج دعم مالي.',
                'decision_reason' => 'قيد الدراسة',
                'status' => 'pending',
                'days_to_decision' => null,
            ],
            [
                'type_name' => 'طلب انسحاب من جامعة',
                'reason' => 'أسباب عائلية تمنعني من إكمال الدراسة حالياً.',
                'decision_reason' => 'تم قبول طلب الانسحاب مع الاحتفاظ بالحق في العودة.',
                'status' => 'approved',
                'days_to_decision' => 3,
            ],
            [
                'type_name' => 'طلب تغيير مشرف',
                'reason' => 'عدم توافق في الرؤية مع المشرف الحالي لمشروع التخرج.',
                'decision_reason' => 'تم تغيير المشرف بناءً على طلب الطالب.',
                'status' => 'approved',
                'days_to_decision' => 4,
            ],
            [
                'type_name' => 'طلب تمديد مشروع تخرج',
                'reason' => 'يحتاج المشروع وقتاً إضافياً لاستكمال التجارب.',
                'decision_reason' => 'تم تمديد المشروع لمدة شهر إضافي.',
                'status' => 'approved',
                'days_to_decision' => 3,
            ],
            [
                'type_name' => 'طلب انضمام لنادي طلابي',
                'reason' => 'أرغب في المشاركة في أنشطة نادي البرمجة.',
                'decision_reason' => 'تم قبول العضوية في النادي.',
                'status' => 'approved',
                'days_to_decision' => 2,
            ],
        ];

        foreach ($students as $index => $student) {
            $numRequests = rand(3, 5);
            $randomTemplates = collect($requestTemplates)->random($numRequests);

            foreach ($randomTemplates as $template) {
                $randomCourse = $courses->random();
                $randomStaff = $staffMembers->random();

                $submissionDate = now()->subDays(rand(1, 30));

                DB::transaction(function () use ($student, $template, $randomCourse, $randomStaff, $submissionDate) {
                    $requestType = RequestType::where('name', $template['type_name'])->first();

                    if (!$requestType) {
                        return; 
                    }

                    $decisionDate = $template['days_to_decision']
                        ? $submissionDate->copy()->addDays($template['days_to_decision'])
                        : null;

                    $this->requestRepo->create([
                        'request_type_id' => $requestType->id,
                        'reason' => $template['reason'],
                        'submission_date' => $submissionDate,
                        'decision_date' => $decisionDate,
                        'decision_reason' => $template['decision_reason'],
                        'student_id' => $student->id,
                        'processed_by_staff_id' => $randomStaff->id,
                        'course_id' => in_array($template['type_name'], ['اعتراض على علامة', 'طلب حذف مقرر', 'طلب إضافة مقرر', 'طلب معادلة مقرر', 'طلب اعادة اختبار'])
                            ? $randomCourse->id
                            : null,
                    ]);
                });
            }
        }
    }
}
