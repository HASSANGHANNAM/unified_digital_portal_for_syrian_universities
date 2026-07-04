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

        // 2. جلب العلاقات (مع إضافة person للمستخدم)
        $request->load([
            'student.person',
            'student.college',
            'student.college.university',
            'requestType',
            'requestUsers.user.person',
            'requestUsers.userSignature',
        ]);

        // 3. اسم نوع الطلب
        $requestTypeName = $request->requestType?->name ?? 'غير محدد';

        // 4. الشعارات
        $appLogo = $this->getAppLogoBase64();
        $collegeLogo = $this->getCollegeLogoBase64($request->student?->college_id);

        // 5. تجهيز التواقيع (الاسم من جدول persons)
        $signatures = $request->requestUsers->map(function ($signature) {
            $data = $signature->toArray();
            $data['user_name'] = $signature->user?->person?->full_name ?? 'غير معروف';

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
        // 7. توليد المحتوى الرئيسي (مع تمرير تاريخ الطلب للعلامة المائية)
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
            'submissionDate' => $request->submission_date ?? $request->created_at ?? now(),
        ])->render();

        // ============================================================
        // 8. توليد الفوتر (التوقيعات) من ملف منفصل
        // ============================================================
        $footerHtml = view('pdf.signatures_footer', [
            'signatures' => $signatures,
        ])->render();

        // ============================================================
        // 9. إنشاء الـ PDF مع إضافة العلامة المائية
        // ============================================================
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'directionality' => 'rtl',
            'default_font' => 'dejavusans',
            'margin_bottom' => 60,
        ]);

        // ✅ إضافة العلامة المائية (نفس توقيت الفوتر: وقت إنشاء الـ PDF)
        $date = now()->format('Y-m-d H:i:s');

        // ✅ الطريقة الصحيحة لتعيين العلامة المائية مع الزاوية والشفافية
        $mpdf->SetWatermarkText($date, 0.06, -45); // (النص, الشفافية, الزاوية)
        $mpdf->showWatermarkText = true;

        $mpdf->SetHTMLFooter($footerHtml);

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
        $repository = app(\App\Repositories\Contracts\StudentCourseRepositoryInterface::class);
        return $repository->getStudentCoursesWithGradesArray($studentId);
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
