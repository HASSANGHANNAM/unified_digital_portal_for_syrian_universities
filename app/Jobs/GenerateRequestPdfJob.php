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
        $initialStatus = $request->status;

        // ============================================================
        // 1. التحقق من أن الطلب في حالة تسمح بتوليد PDF
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
        $academicYears = [];
        $sanctions = [];
        $suspensionRequests = [];
        $studentStatus = 'غير محدد';
        $certificateData = null;
        $equivalencyData = null;
        $academicYear = '2024-2025'; // القيمة الافتراضية

        if (str_contains($requestTypeName, 'كشف علامات')) {
            $grades = $this->getStudentGrades($request->student_id);
        } elseif ($requestTypeName === 'حياة جامعية أو تسلسل دراسي أو بيان وضع') {
            $academicData = $this->getStudentAcademicData($request->student_id);
            $academicYears = $academicData['academicYears'] ?? [];
            $sanctions = $academicData['sanctions'] ?? [];
            $suspensionRequests = $academicData['suspensionRequests'] ?? [];
            $studentStatus = $academicData['studentStatus'] ?? 'غير محدد';
        } elseif ($requestTypeName === 'شهادة تخرج') {
            $certificateData = $this->getGraduationData($request->student_id);
        } elseif ($requestTypeName === 'طلب معادلة') {
            $equivalencyData = $this->getEquivalencyData($request->id);
        } elseif ($requestTypeName === 'وثيقة دوام') {
            $attendanceData = $this->getAttendanceCertificateData($request->student_id);
            $academicYear = $attendanceData['academicYear'] ?? '2024-2025';
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
            'sanctions' => $sanctions,
            'suspensionRequests' => $suspensionRequests,
            'studentStatus' => $studentStatus,
            'certificateData' => $certificateData,
            'equivalencyData' => $equivalencyData,
            'academicYear' => $academicYear, // 🔥 المتغير الجديد
            'signatures' => $signatures,
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
        // 11. إعادة الحالة النهائية بعد اكتمال التوليد
        //     - pending => waiting_{first_role}
        //     - generating_{role}_pdf => waiting_{next_role} أو completed
        //     - completed يبقى completed عندما لا يوجد دور تالٍ
        // ============================================================
        $finalStatus = $this->resolveFinalStatusAfterGeneration($request, $initialStatus);

        if ($finalStatus !== null && $request->status !== $finalStatus) {
            $request->status = $finalStatus;
            $request->save();

            Log::info('تم تحديث حالة الطلب بعد توليد PDF', [
                'request_id' => $request->id,
                'from_status' => $initialStatus,
                'to_status' => $finalStatus,
            ]);
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
    // ============= دوال مساعدة لجلب البيانات حسب النوع =============
    // ================================================================

    /**
     * جلب العلامات الدراسية (كشف علامات)
     */
    protected function getStudentGrades(int $studentId): array
    {
        $repository = app(\App\Repositories\Contracts\StudentCourseRepositoryInterface::class);
        return $repository->getStudentCoursesWithGradesArray($studentId);
    }

    /**
     * جلب جميع بيانات الحياة الجامعية (السنوات، العقوبات، طلبات الإيقاف، حالة الطالب)
     * 🔥 هذه الدالة جاهزة لاستبدال البيانات الوهمية ببيانات حقيقية من الـ Repository
     * 
     * @param int $studentId
     * @return array
     */
    protected function getStudentAcademicData(int $studentId): array
    {
        // ✅ TODO: استبدل هذا الكود باستدعاء الـ Repository الخاص بك
        // $repository = app(\App\Repositories\Contracts\StudentAcademicRepositoryInterface::class);
        // return $repository->getStudentAcademicData($studentId);

        // 👇 بيانات وهمية للتجربة (سيتم استبدالها لاحقاً)
        return [
            'studentStatus' => 'مستمر',
            'academicYears' => [
                ['year' => 'السنة الأولى', 'status' => 'منقول'],
                ['year' => 'السنة الثانية', 'status' => 'منقول'],
                ['year' => 'السنة الثالثة', 'status' => 'منقول'],
                ['year' => 'السنة الرابعة', 'status' => 'ناجح'],
            ],
            'sanctions' => [
                ['name' => 'إنذار', 'start_date' => '2025-01-01', 'end_date' => '2025-06-01'],
                ['name' => 'فصل مؤقت', 'start_date' => '2025-07-01', 'end_date' => '2025-09-01'],
            ],
            'suspensionRequests' => [
                ['date' => '2025-01-10'],
                ['date' => '2025-03-15'],
            ],
        ];
    }

    /**
     * جلب بيانات وثيقة الدوام (بيانات ثابتة حالياً)
     * 🔥 هذه الدالة جاهزة لاستبدال البيانات الوهمية ببيانات حقيقية من الـ Repository
     * 
     * @param int $studentId
     * @return array
     */
    protected function getAttendanceCertificateData(int $studentId): array
    {
        // ✅ TODO: استبدل هذا الكود باستدعاء الـ Repository الخاص بك
        // $repository = app(\App\Repositories\Contracts\AttendanceCertificateRepositoryInterface::class);
        // return $repository->getAttendanceCertificateData($studentId);

        // 👇 بيانات وهمية للتجربة (سيتم استبدالها لاحقاً)
        return [
            'academicYear' => '2024-2025',
        ];
    }

    /**
     * جلب السنوات الدراسية (للحياة الجامعية) - احتفظ بها للتوافق مع الكود القديم
     */
    protected function getStudentAcademicYears(int $studentId): array
    {
        return [
            ['year' => '2020-2021', 'gpa' => 3.2, 'hours' => 30, 'status' => 'مكتملة'],
            ['year' => '2021-2022', 'gpa' => 3.5, 'hours' => 33, 'status' => 'مكتملة'],
            ['year' => '2022-2023', 'gpa' => 3.8, 'hours' => 27, 'status' => 'جاري'],
            ['year' => '2023-2024', 'gpa' => 3.9, 'hours' => 24, 'status' => 'جاري'],
        ];
    }

    /**
     * جلب بيانات التخرج (شهادة تخرج)
     */
    protected function getGraduationData(int $studentId): array
    {
        return [
            'major' => 'هندسة المعلوماتية',
            'gpa' => 3.7,
            'graduation_date' => '2024-06-15',
            'grade' => 'جيد جداً',
        ];
    }

    /**
     * جلب مواد المعادلة (طلب معادلة)
     */
    protected function getEquivalencyData(int $requestId): array
    {
        return [
            ['name' => 'رياضيات 1', 'source_university' => 'جامعة دمشق', 'mark' => 85, 'hours' => 3],
            ['name' => 'فيزياء 1', 'source_university' => 'جامعة حلب', 'mark' => 78, 'hours' => 3],
            ['name' => 'برمجة 1', 'source_university' => 'جامعة تشرين', 'mark' => 92, 'hours' => 4],
        ];
    }

    /**
     * تحديد الحالة النهائية بعد انتهاء توليد PDF.
     *
     * هذا يحافظ على تسلسل الحالات نفسه الموجود في الـ Repository:
     * pending -> waiting_{first_role}
     * generating_{role}_pdf -> waiting_{next_role} أو completed
     */
    protected function resolveFinalStatusAfterGeneration(Request $request, string $initialStatus): ?string
    {
        $requiredRoles = $request->requestType?->getRequiredRoles() ?? [];

        if ($initialStatus === 'pending') {
            $firstRole = $requiredRoles[0] ?? null;

            return $firstRole ? 'waiting_' . $firstRole : null;
        }

        if ($initialStatus === 'completed') {
            return 'completed';
        }

        if (! preg_match('/^generating_(.+)_pdf$/', $initialStatus, $matches)) {
            return null;
        }

        $currentRole = $matches[1] ?? null;

        if ($currentRole === null || $requiredRoles === []) {
            return null;
        }

        $currentIndex = array_search($currentRole, $requiredRoles, true);

        if ($currentIndex === false) {
            return null;
        }

        $nextIndex = $currentIndex + 1;

        if ($nextIndex >= count($requiredRoles)) {
            return 'completed';
        }

        return 'waiting_' . $requiredRoles[$nextIndex];
    }

    /**
     * جلب شعار الكلية (Base64)
     */
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

    /**
     * جلب شعار الجامعة (Base64)
     */
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
