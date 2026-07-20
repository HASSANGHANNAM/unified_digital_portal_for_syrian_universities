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

        // ============================================================
        // 1. التحقق من أن الطلب في حالة تسمح بتوليد PDF
        //    - pending: أول توليد (بدون توقيعات)
        //    - generating_...: توليد بعد كل توقيع (مع التواقيع المسجلة)
        // ============================================================
        $allowedStatuses = ['pending'];
        $isGenerating = str_starts_with($request->status, 'generating_');

        if (!in_array($request->status, $allowedStatuses) && !$isGenerating) {
            Log::warning('الطلب ليس في حالة تسمح بتوليد PDF', [
                'request_id' => $request->id,
                'status' => $request->status,
            ]);
            return;
        }

        // ============================================================
        // 2. جلب العلاقات المطلوبة
        // ============================================================
        $request->load([
            'student.person',
            'student.college',
            'student.college.university',
            'requestType',
            // جلب التواقيع المسجلة فقط (approved)
            'requestUsers' => function ($query) {
                $query->where('status', 'approved')
                    ->with(['user.person', 'userSignature']);
            },
        ]);

        // ============================================================
        // 3. اسم نوع الطلب
        // ============================================================
        $requestTypeName = $request->requestType?->name ?? 'غير محدد';

        // ============================================================
        // 4. الشعارات
        // ============================================================
        $appLogo = $this->getAppLogoBase64();
        $universityLogo = $this->getUniversityLogoBase64($request->student?->college?->university_id);

        // ============================================================
        // 5. تجهيز التواقيع المسجلة (approved)
        // ============================================================
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

        // ============================================================
        // 6. البيانات حسب نوع الطلب
        // ============================================================
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
        // 7. توليد المحتوى الرئيسي
        // ============================================================
        $mainHtml = view('pdf.request_main', [
            'request' => $request,
            'requestTypeName' => $requestTypeName,
            'appLogo' => $appLogo,
            'universityLogo' => $universityLogo,
            'grades' => $grades,
            'academicYears' => $academicYears,
            'certificateData' => $certificateData,
            'equivalencyData' => $equivalencyData,
            'signatures' => $signatures, // تمرير التواقيع المسجلة فقط
            'submissionDate' => $request->submission_date ?? $request->created_at ?? now(),
        ])->render();

        // ============================================================
        // 8. توليد الفوتر (التوقيعات)
        // ============================================================
        $footerHtml = view('pdf.signatures_footer', [
            'signatures' => $signatures,
        ])->render();

        // ============================================================
        // 9. إنشاء الـ PDF مع العلامة المائية
        // ============================================================
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'directionality' => 'rtl',
            'default_font' => 'dejavusans',
            'margin_bottom' => 60,
        ]);

        $date = now()->format('Y-m-d H:i:s');
        $mpdf->SetWatermarkText($date, 0.06, -45);
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

        // تحديث مسار PDF في قاعدة البيانات
        $request->pdf_path = $path;
        $request->save();

        // ============================================================
        // 11. 🔥 تغيير الحالة إلى waiting_{first_role} إذا كانت pending
        // ============================================================
        if ($request->status === 'pending') {
            $firstRole = $request->requestType?->getRequiredRoles()[0] ?? null;
            if ($firstRole) {
                $request->status = 'waiting_' . $firstRole;
                $request->save();

                Log::info('تم تغيير حالة الطلب إلى waiting_' . $firstRole, [
                    'request_id' => $request->id,
                ]);
            }
        }

        // ============================================================
        // 12. تسجيل النجاح
        // ============================================================
        Log::info('تم توليد PDF للطلب', [
            'request_id' => $request->id,
            'pdf_path' => $path,
            'type' => $requestTypeName,
            'signatures_count' => count($signatures),
            'status' => $request->status,
        ]);
    }

    // ================================================================
    // دوال مساعدة (لم تتغير)
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

    protected function getUniversityLogoBase64(?int $universityId): ?string
    {
        if (!$universityId) {
            return null;
        }

        $university = \App\Models\University::find($universityId);
        if (!$university || !$university->logo_path) {
            return null;
        }

        $path = $university->logo_path;
        if (!Storage::disk('local')->exists($path)) {
            return null;
        }

        $binary = Storage::disk('local')->get($path);
        return 'data:image/png;base64,' . base64_encode($binary);
    }
}
