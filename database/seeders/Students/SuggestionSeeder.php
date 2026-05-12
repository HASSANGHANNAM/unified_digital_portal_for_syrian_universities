<?php

namespace Database\Seeders\Students;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\SuggestionRepositoryInterface;
use App\Models\Student;

class SuggestionSeeder extends Seeder
{
    public function __construct(
        private SuggestionRepositoryInterface $suggestionRepo,
    ) {}

    public function run(): void
    {
        $students = Student::with('person')->get();

        // اقتراحات واقعية في الجامعات السورية
        $suggestionsList = [
            'تحسين جودة الإنترنت في المختبرات',
            'زيادة عدد الساعات العملية لمقرر البرمجة',
            'توفير كتب مرجعية في المكتبة',
            'تأخير موعد الامتحانات النهائية',
            'تقليل عدد الطلاب في القاعات الدراسية',
            'توفير وسائل نقل للطلاب من المناطق البعيدة',
            'زيادة المنح الدراسية للطلاب المتفوقين',
            'تحسين نظام التسجيل الإلكتروني للمقررات',
            'إقامة دورات تقوية للطلاب الضعفاء',
            'توفير مختبرات حاسوب مجهزة بشكل أفضل',
            'زيادة النشاطات الرياضية والثقافية',
            'تحسين جودة الطعام في الكافتيريا',
            'توفير مواقف للسيارات داخل الحرم الجامعي',
            'تفعيل دور المرشد الأكاديمي',
            'تقليل رسوم الساعات المعتمدة',
            'إضافة مقررات اختيارية جديدة ومتنوعة',
            'تنظيم ورش عمل تدريبية مع شركات',
            'تحسين الإضاءة والتهوية في القاعات',
            'توفير أجهزة عرض حديثة في المدرجات',
            'زيادة فترة استعارة الكتب من المكتبة',
            'تفعيل نظام الامتحانات الإلكترونية',
            'إنشاء نادي رياضي مجهز',
            'توفير خدمة النت المجاني للطلاب',
            'تحسين نظام الإعلام الجامعي',
            'تنظيم رحلات علمية للطلاب',
        ];

        $statuses = ['قيد المراجعة', 'قيد الدراسة', 'تم الرفض', 'تم القبول', 'تحت التنفيذ'];

        $submissionDates = [
            now()->subDays(rand(1, 30)),
            now()->subDays(rand(1, 60)),
            now()->subDays(rand(1, 90)),
            now(),
        ];

        $totalSuggestions = 0;

        foreach ($students as $student) {

            $numSuggestions = rand(0, 3);

            if ($student->student_id_number == '20210001' || $student->student_id_number == '20210004') {
                $numSuggestions = rand(2, 4);
            }

            if ($numSuggestions == 0) {
                continue;
            }

            $randomSuggestions = collect($suggestionsList)->random($numSuggestions);

            foreach ($randomSuggestions as $suggestionText) {
                $status = $statuses[array_rand($statuses)];
                $submissionDate = $submissionDates[array_rand($submissionDates)];

                DB::transaction(function () use ($student, $suggestionText, $status, $submissionDate) {
                    $this->suggestionRepo->create([
                        'content' => $suggestionText,
                        'submission_date' => $submissionDate,
                        'status' => $status,
                        'student_id' => $student->id,
                    ]);
                });

                $totalSuggestions++;
            }
        }

 
    }
}
