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
                'days_to_decision' => 3,
            ],
            [
                'type_name' => 'طلب حذف مقرر',
                'reason' => 'المقرر يسبب صعوبة كبيرة ويتعارض مع مواعيد عملي.',
                'decision_reason' => 'تم قبول الطلب وحذف المقرر من جدول الطالب.',
                'days_to_decision' => 2,
            ],
            [
                'type_name' => 'طلب إضافة مقرر',
                'reason' => 'أريد إضافة مقرر اختياري لتكملة عدد الساعات المطلوبة.',
                'decision_reason' => 'تمت الموافقة على إضافة المقرر بعد التأكد من توفر المقاعد.',
                'days_to_decision' => 2,
            ],
            [
                'type_name' => 'طلب تأجيل فصل دراسي',
                'reason' => 'ظروف صحية تمنعني من متابعة الدراسة هذا الفصل.',
                'decision_reason' => 'تم قبول طلب التأجيل بناءً على التقرير الطبي.',
                'days_to_decision' => 5,
            ],
            [
                'type_name' => 'طلب استرجاع رسوم',
                'reason' => 'تم حذف المقرر ولم أعد بحاجة للرسوم المدفوعة.',
                'decision_reason' => 'تمت الموافقة على استرجاع 100% من الرسوم.',
                'days_to_decision' => 7,
            ],
            [
                'type_name' => 'طلب شهادة تخرج',
                'reason' => 'أنهيت متطلبات التخرج وأحتاج الشهادة للتقديم على وظيفة.',
                'decision_reason' => 'تم تجهيز شهادة التخرج وجاهزة للاستلام.',
                'days_to_decision' => 10,
            ],
            [
                'type_name' => 'طلب معادلة مقرر',
                'reason' => 'درست مقرر مشابه في جامعة سابقة وأريد معادلته.',
                'decision_reason' => 'تمت معادلة المقرر بعد مطابقة المحتوى الدراسي.',
                'days_to_decision' => 6,
            ],
            [
                'type_name' => 'طلب تحويل مسار',
                'reason' => 'أرغب في التحويل من هندسة البرمجيات إلى الذكاء الاصطناعي.',
                'decision_reason' => 'تمت الموافقة على التحويل بعد استيفاء المعدل المطلوب.',
                'days_to_decision' => null,
            ],
            [
                'type_name' => 'طلب اعادة اختبار',
                'reason' => 'تغيب عن الاختبار النهائي بسبب ظرف طارئ.',
                'decision_reason' => 'تم تحديد موعد للاختبار البديل.',
                'days_to_decision' => 4,
            ],
            [
                'type_name' => 'طلب تدريب عملي',
                'reason' => 'أحتاج إلى موافقة على التدريب في شركة معينة.',
                'decision_reason' => 'تمت الموافقة على موقع التدريب.',
                'days_to_decision' => 5,
            ],
            [
                'type_name' => 'طلب منحة دراسية',
                'reason' => 'لدي تفوق دراسي وأحتاج دعم مالي.',
                'decision_reason' => 'قيد الدراسة',
                'days_to_decision' => null,
            ],
            [
                'type_name' => 'طلب انسحاب من جامعة',
                'reason' => 'أسباب عائلية تمنعني من إكمال الدراسة حالياً.',
                'decision_reason' => 'تم قبول طلب الانسحاب مع الاحتفاظ بالحق في العودة.',
                'days_to_decision' => 3,
            ],
            [
                'type_name' => 'طلب تغيير مشرف',
                'reason' => 'عدم توافق في الرؤية مع المشرف الحالي لمشروع التخرج.',
                'decision_reason' => 'تم تغيير المشرف بناءً على طلب الطالب.',
                'days_to_decision' => 4,
            ],
            [
                'type_name' => 'طلب تمديد مشروع تخرج',
                'reason' => 'يحتاج المشروع وقتاً إضافياً لاستكمال التجارب.',
                'decision_reason' => 'تم تمديد المشروع لمدة شهر إضافي.',
                'days_to_decision' => 3,
            ],
            [
                'type_name' => 'طلب انضمام لنادي طلابي',
                'reason' => 'أرغب في المشاركة في أنشطة نادي البرمجة.',
                'decision_reason' => 'تم قبول العضوية في النادي.',
                'days_to_decision' => 2,
            ],
        ];

        // -------------------- البيانات العشوائية --------------------
        foreach ($students as $student) {
            // اختيار عدد عشوائي من الطلبات بين 3 و 5، بشرط ألا يزيد عن عدد القوالب
            $numTemplates = count($requestTemplates);
            $numRequests = rand(3, min(5, $numTemplates));
            $randomTemplates = collect($requestTemplates)->random($numRequests);

            foreach ($randomTemplates as $template) {
                $randomCourse = $courses->random();
                $randomStaff = $staffMembers->random();
                $submissionDate = now()->subDays(rand(1, 30));

                DB::transaction(function () use ($student, $template, $randomCourse, $randomStaff, $submissionDate) {
                    $requestType = RequestType::where('name', $template['type_name'])->first();
                    if (!$requestType) return;

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

        // -------------------- البيانات الثابتة من dummyData.ts (بدون حقل status) --------------------
        $staticRequests = [
            [
                'request_type_name' => 'طلب تأجيل امتحان',
                'reason' => 'وعكة صحية طارئة',
                'submission_date' => '2025-03-10',
                'decision_date' => '2025-03-12',
                'decision_reason' => 'تم قبول الطلب بعد تقديم التقرير الطبي',
                'student_name' => 'أحمد محمد العلي',
                'staff_name' => 'أحمد محمود',
                'course_code' => 'CS201',
            ],
            [
                'request_type_name' => 'طلب اعتذار عن فصل دراسي',
                'reason' => 'ظروف عائلية',
                'submission_date' => '2025-04-01',
                'decision_date' => null,
                'decision_reason' => null,
                'student_name' => 'يوسف سامر الحموي',
                'staff_name' => 'محمد علي',
                'course_code' => null,
            ],
            [
                'request_type_name' => 'طلب إعادة تصحيح',
                'reason' => 'يوجد خطأ في جمع الدرجات',
                'submission_date' => '2025-02-20',
                'decision_date' => '2025-02-25',
                'decision_reason' => 'تمت إعادة التصحيح وتبين وجود خطأ',
                'student_name' => 'نورا علي حسين',
                'staff_name' => 'محمد علي',
                'course_code' => 'PHY101',
            ],
            [
                'request_type_name' => 'طلب تأجيل امتحان',
                'reason' => 'حالة وفاة أحد الأقارب',
                'submission_date' => '2025-05-01',
                'decision_date' => null,
                'decision_reason' => null,
                'student_name' => 'رامي عدنان الخطيب',
                'staff_name' => 'أحمد محمود',
                'course_code' => 'ECO101',
            ],
        ];

        foreach ($staticRequests as $data) {
            $requestType = RequestType::where('name', $data['request_type_name'])->first();
            if (!$requestType) continue;

            $student = Student::whereHas('person', fn($q) => $q->where('full_name', $data['student_name']))->first();
            if (!$student) continue;

            $staff = Staff::whereHas('person', fn($q) => $q->where('full_name', $data['staff_name']))->first();
            if (!$staff) continue;

            $course = null;
            if ($data['course_code']) {
                $course = Course::where('code', $data['course_code'])->first();
                if (!$course) continue;
            }

            // التحقق من عدم التكرار
            $exists = \App\Models\Request::where('student_id', $student->id)
                ->where('request_type_id', $requestType->id)
                ->where('submission_date', $data['submission_date'])
                ->when($course, fn($q) => $q->where('course_id', $course->id))
                ->exists();

            if (!$exists) {
                DB::transaction(function () use ($data, $requestType, $student, $staff, $course) {
                    $this->requestRepo->create([
                        'request_type_id' => $requestType->id,
                        'reason' => $data['reason'],
                        'submission_date' => $data['submission_date'],
                        'decision_date' => $data['decision_date'],
                        'decision_reason' => $data['decision_reason'],
                        'student_id' => $student->id,
                        'processed_by_staff_id' => $staff->id,
                        'course_id' => $course ? $course->id : null,
                        // لا نرسل 'status' لأن العمود غير موجود
                    ]);
                });
            }
        }
    }
}
