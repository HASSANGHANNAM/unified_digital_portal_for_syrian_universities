<?php

namespace App\Jobs;

use App\Models\Request;
use App\Services\Traits\AppLogoTrait;
use App\Services\Traits\RequestValidationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;

class GenerateRequestPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AppLogoTrait, RequestValidationTrait;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(): void
    {
        ini_set('memory_limit', '512M');
        $request = $this->request;

        // 1. التحقق من اكتمال الطلب
        if (!$this->isRequestFullyApproved($request->id)) {
            Log::warning('الطلب غير مكتمل أو غير موافق عليه، لن يتم توليد PDF', [
                'request_id' => $request->id,
                'status' => $request->status,
            ]);
            return;
        }

        // 2. جلب العلاقات
        $request->load([
            'student.person',
            'student.college',
            'student.college.university',
            'requestType',
            'requestUsers.user',
            'requestUsers.userSignature',
        ]);

        // 3. اسم نوع الطلب
        $requestTypeName = $request->requestType?->name ?? 'غير محدد';

        // 4. الشعارات
        $appLogo = $this->getAppLogoBase64();
        $collegeLogo = $this->getCollegeLogoBase64($request->student?->college_id);

        // 5. تجهيز التواقيع
        $signatures = $request->requestUsers->map(function ($signature) {
            $data = $signature->toArray();
            $data['user_name'] = $signature->user?->username ?? 'غير معروف';

            if ($signature->userSignature) {
                $path = $signature->userSignature->path ?? null;
                if (!$path && $signature->userSignature->signature_uuid) {
                    $path = 'private/signatures/' . $signature->userSignature->signature_uuid . '.png';
                }
                if ($path && Storage::disk('local')->exists($path)) {
                    $binary = Storage::disk('local')->get($path);
                    $data['signature_base64'] = 'data:image/png;base64,' . base64_encode($binary);
                }
            }

            return $data;
        })->toArray();

        // 6. البيانات حسب نوع الطلب (باستخدام str_contains)
        $grades = null;
        $academicYears = null;
        $certificateData = null;
        $equivalencyData = null;

        if (str_contains($requestTypeName, 'كشف علامات')) {
            $grades = $this->getStudentGrades($request->student_id);
        } elseif ($requestTypeName === 'حياة جامعية') {
            $academicYears = $this->getStudentAcademicYears($request->student_id);
        } elseif ($requestTypeName === 'شهادة تخرج') {
            $certificateData = $this->getGraduationData($request->student_id);
        } elseif ($requestTypeName === 'طلب معادلة') {
            $equivalencyData = $this->getEquivalencyData($request->id);
        }

        // ============================================================
        // ✅ سجلات للتحقق
        // ============================================================
        Log::info('========== بداية توليد PDF ==========');
        Log::info('requestTypeName: ' . $requestTypeName);
        Log::info('grades: ' . json_encode($grades));
        Log::info('=====================================');

        // ============================================================
        // 7. توليد المحتوى الرئيسي
        // ============================================================
        $mainHtml = view('pdf.request_main', [
            'request' => $request,
            'requestTypeName' => $requestTypeName,
            'appLogo' => $appLogo,
            'collegeLogo' => $collegeLogo,
            'grades' => $grades,
            'academicYears' => $academicYears,
            'certificateData' => $certificateData,
            'equivalencyData' => $equivalencyData,
            'signatures' => $signatures,
        ])->render();

        // ============================================================
        // 8. توليد الفوتر (التوقيعات) من ملف منفصل
        // ============================================================
        // توليد الفوتر
        $footerHtml = view('pdf.signatures_footer', [
            'signatures' => $signatures,
        ])->render();

        // إنشاء الـ PDF مع هوامش مناسبة
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'directionality' => 'rtl',
            'default_font' => 'dejavusans',
            'margin_bottom' => 60,
        ]);

        $mpdf->SetHTMLFooter($footerHtml);

        // (اختياري) إذا كانت نسخة mPDF تدعمه
        if (method_exists($mpdf, 'SetAutoBottomMargin')) {
            $mpdf->SetAutoBottomMargin('stretch');
        }

        $mpdf->WriteHTML($mainHtml);
        $pdfContent = $mpdf->Output('', 'S');
        // ============================================================
        // 10. حفظ الملف
        // ============================================================
        $pdfUuid = (string) Str::uuid();
        $directory = 'private/requests/' . $request->student_id;
        $path = $directory . '/' . $pdfUuid . '.pdf';

        if (!Storage::disk('local')->exists($directory)) {
            Storage::disk('local')->makeDirectory($directory);
        }

        Storage::disk('local')->put($path, $pdfContent);

        $request->pdf_path = $path;
        $request->save();

        Log::info('تم توليد PDF للطلب', [
            'request_id' => $request->id,
            'pdf_path' => $path,
            'type' => $requestTypeName,
        ]);
    }

    // ================================================================
    // دوال مساعدة
    // ================================================================
    protected function getStudentGrades(int $studentId): array
    {
        return [
            'courses' => [
                // ========== مواد ناجحة (passed) ==========
                [
                    'course_name' => 'رياضيات 1',
                    'code' => 'MATH101',
                    'total' => 85,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'فيزياء 1',
                    'code' => 'PHYS101',
                    'total' => 92,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'كيمياء عامة',
                    'code' => 'CHEM101',
                    'total' => 78,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'أحياء عامة',
                    'code' => 'BIO101',
                    'total' => 88,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'برمجة 1',
                    'code' => 'CS101',
                    'total' => 95,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'قواعد بيانات',
                    'code' => 'CS201',
                    'total' => 82,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'شبكات حاسوب',
                    'code' => 'CS301',
                    'total' => 76,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'نظم تشغيل',
                    'code' => 'CS302',
                    'total' => 89,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'هندسة برمجيات',
                    'code' => 'CS401',
                    'total' => 91,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'ذكاء اصطناعي',
                    'code' => 'CS402',
                    'total' => 87,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'تحليل عددي',
                    'code' => 'MATH201',
                    'total' => 80,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'إحصاء',
                    'code' => 'MATH202',
                    'total' => 79,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'فيزياء 2',
                    'code' => 'PHYS201',
                    'total' => 84,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'كيمياء عضوية',
                    'code' => 'CHEM201',
                    'total' => 73,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'لغة عربية',
                    'code' => 'ARAB101',
                    'total' => 90,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'لغة إنجليزية',
                    'code' => 'ENG101',
                    'total' => 94,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'حقوق إنسان',
                    'code' => 'LAW101',
                    'total' => 86,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'فلسفة',
                    'code' => 'PHIL101',
                    'total' => 77,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'علم نفس',
                    'code' => 'PSY101',
                    'total' => 81,
                    'status' => 'passed',
                ],
                [
                    'course_name' => 'تاريخ حضارة',
                    'code' => 'HIST101',
                    'total' => 83,
                    'status' => 'passed',
                ],

                // ========== مواد ناجحة بالمساعدة (passed_with_assistance) ==========
                [
                    'course_name' => 'ميكانيكا كلاسيكية',
                    'code' => 'PHYS301',
                    'total' => 58,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'كهرباء ومغناطيس',
                    'code' => 'PHYS302',
                    'total' => 59,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'ديناميكا حرارية',
                    'code' => 'PHYS303',
                    'total' => 60,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'كيمياء تحليلية',
                    'code' => 'CHEM301',
                    'total' => 57,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'كيمياء فيزيائية',
                    'code' => 'CHEM302',
                    'total' => 58,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'رياضيات 2',
                    'code' => 'MATH102',
                    'total' => 59,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'جبر خطي',
                    'code' => 'MATH203',
                    'total' => 56,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'تفاضل وتكامل 2',
                    'code' => 'MATH204',
                    'total' => 58,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'أحياء دقيقة',
                    'code' => 'BIO201',
                    'total' => 60,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'أحياء جزيئية',
                    'code' => 'BIO202',
                    'total' => 57,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'برمجة 2',
                    'code' => 'CS102',
                    'total' => 59,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'هياكل بيانات',
                    'code' => 'CS202',
                    'total' => 58,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'خوارزميات',
                    'code' => 'CS203',
                    'total' => 60,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'نظرية المعلومات',
                    'code' => 'CS303',
                    'total' => 59,
                    'status' => 'passed_with_assistance',
                ],
                [
                    'course_name' => 'أمن سيبراني',
                    'code' => 'CS403',
                    'total' => 58,
                    'status' => 'passed_with_assistance',
                ],

                // ========== مواد راسبة (failed) ==========
                [
                    'course_name' => 'ميكانيكا الموائع',
                    'code' => 'PHYS401',
                    'total' => 42,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'فيزياء حديثة',
                    'code' => 'PHYS402',
                    'total' => 38,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'كيمياء عضوية 2',
                    'code' => 'CHEM303',
                    'total' => 45,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'كيمياء حيوية',
                    'code' => 'CHEM304',
                    'total' => 40,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'رياضيات 3',
                    'code' => 'MATH305',
                    'total' => 35,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'معادلات تفاضلية',
                    'code' => 'MATH306',
                    'total' => 44,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'أحياء بيئية',
                    'code' => 'BIO301',
                    'total' => 48,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'جيولوجيا',
                    'code' => 'GEO101',
                    'total' => 39,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'فلك',
                    'code' => 'AST101',
                    'total' => 41,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'نظرية الأعداد',
                    'code' => 'MATH401',
                    'total' => 36,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'طوبولوجيا',
                    'code' => 'MATH402',
                    'total' => 43,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'فيزياء حرارية',
                    'code' => 'PHYS403',
                    'total' => 47,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'بصريات',
                    'code' => 'PHYS404',
                    'total' => 49,
                    'status' => 'failed',
                ],
                [
                    'course_name' => 'كيمياء صناعية',
                    'code' => 'CHEM401',
                    'total' => 50,
                    'status' => 'failed', // قريب من النجاح لكن راسب
                ],
                [
                    'course_name' => 'هندسة كهربائية',
                    'code' => 'EE101',
                    'total' => 33,
                    'status' => 'failed',
                ],
            ]
        ];
    }

    protected function getStudentAcademicYears(int $studentId): array
    {
        return [
            ['year' => '2020-2021', 'gpa' => 3.2, 'hours' => 30, 'status' => 'مكتملة'],
            ['year' => '2021-2022', 'gpa' => 3.5, 'hours' => 33, 'status' => 'مكتملة'],
            ['year' => '2022-2023', 'gpa' => 3.8, 'hours' => 27, 'status' => 'جاري'],
            ['year' => '2023-2024', 'gpa' => 3.9, 'hours' => 24, 'status' => 'جاري'],
        ];
    }

    protected function getGraduationData(int $studentId): array
    {
        return [
            'major' => 'هندسة المعلوماتية',
            'gpa' => 3.7,
            'graduation_date' => '2024-06-15',
            'grade' => 'جيد جداً',
        ];
    }

    protected function getEquivalencyData(int $requestId): array
    {
        return [
            ['name' => 'رياضيات 1', 'source_university' => 'جامعة دمشق', 'mark' => 85, 'hours' => 3],
            ['name' => 'فيزياء 1', 'source_university' => 'جامعة حلب', 'mark' => 78, 'hours' => 3],
            ['name' => 'برمجة 1', 'source_university' => 'جامعة تشرين', 'mark' => 92, 'hours' => 4],
        ];
    }

    protected function getCollegeLogoBase64(?int $collegeId): ?string
    {
        if (!$collegeId) {
            return null;
        }

        $college = \App\Models\College::find($collegeId);
        if (!$college || !$college->logo_path) {
            return null;
        }

        $path = $college->logo_path;
        if (!Storage::disk('local')->exists($path)) {
            return null;
        }

        $binary = Storage::disk('local')->get($path);
        return 'data:image/png;base64,' . base64_encode($binary);
    }
}
