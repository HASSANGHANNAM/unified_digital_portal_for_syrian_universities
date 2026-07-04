<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طلب {{ $requestTypeName }}</title>
    <style>
        /* ====================================================== */
        /*                     الإعدادات العامة                    */
        /* ====================================================== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            direction: rtl;
            background: #fff;
            color: #1a1a1a;
            font-size: 14px;
            line-height: 1.6;
        }

        /* ====================================================== */
        /*      جدول رئيسي يملأ كامل ارتفاع الصفحة               */
        /* ====================================================== */
        .page-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }
        .page-table .content-row {
            height: auto;
        }
        .page-table .signature-row {
            vertical-align: bottom;
            height: 1px;
        }

        /* ====================================================== */
        /*                  معلومات الطالب                         */
        /* ====================================================== */
        .student-info {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }
        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 4px 8px;
            font-size: 13px;
            vertical-align: top;
        }
        .student-info .label {
            font-weight: bold;
            color: #495057;
            width: 120px;
        }

        /* ====================================================== */
        /*              المحتوى المتغير                           */
        /* ====================================================== */
        .content {
            min-height: 120px;
            margin-bottom: 20px;
        }
        .content h3 {
            color: #2c3e50;
            border-right: 5px solid #3498db;
            padding-right: 10px;
            margin-bottom: 12px;
            font-size: 16px;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .content table th {
            background-color: #2c3e50;
            color: #fff;
            padding: 6px 10px;
            text-align: center;
            font-size: 12px;
        }
        .content table td {
            border: 1px solid #dee2e6;
            padding: 5px 10px;
            text-align: center;
            font-size: 12px;
        }
        .content table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .certificate-box {
            text-align: center;
            border: 2px solid #2c3e50;
            border-radius: 10px;
            padding: 20px 15px;
            background: #fefefe;
        }
        .certificate-box .student-name {
            font-size: 16px;
            font-weight: bold;
            color: #2980b9;
            margin: 8px 0;
        }
        .content .free-text {
            border: 1px solid #ced4da;
            padding: 12px;
            min-height: 80px;
            background: #fff;
            border-radius: 4px;
            font-size: 13px;
        }
        .empty-message {
            text-align: center;
            color: #888;
            padding: 15px;
        }

        /* ====================================================== */
        /*                   الفوتر النصي                          */
        /* ====================================================== */
        .footer-text {
            text-align: center;
            border-top: 1px solid #dee2e6;
            padding-top: 12px;
            margin-top: 20px;
            font-size: 11px;
            color: #6c757d;
        }
        .footer-text p {
            margin: 2px 0;
        }

        /* ====================================================== */
        /*              التوقيعات (6 خانات أفقية)                  */
        /* ====================================================== */
        .signature-wrapper {
            border-top: 2px solid #2c3e50;
            padding-top: 8px;
            margin-top: 0;
            width: 100%;
        }
        .signatures-title {
            text-align: center;
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 3px;
        }
        .signature-grid {
            width: 100%;
        }
        .signature-grid table {
            width: 100%;
            border-collapse: collapse;
        }
        .signature-grid td {
            width: 16.66%;
            text-align: center;
            padding: 5px 4px;
            vertical-align: top;
            border: none;
        }
        .signature-grid td.has-signature:not(:first-child) {
            border-left: 1px solid #ccc;
        }
        .signature-grid td:not(.has-signature) {
            border: none !important;
            background: transparent;
            padding: 0;
        }
        .sig-name {
            font-size: 10px;
            color: #1a1a1a;
            margin-bottom: 4px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }
        .sig-item img {
            width: 60px !important;
            height: 30px !important;
            object-fit: contain;
            margin: 3px 0;
        }
        .sig-date {
            font-size: 5px;
            color: #1a1a1a;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <!-- ========================================================= -->
    <!-- جدول رئيسي: الصف الأول للمحتوى، الثاني للتوقيعات          -->
    <!-- ========================================================= -->
    <table class="page-table">
        <tr class="content-row">
            <td>
                <!-- ====== استدعاء الهيدر (يظهر فقط في الصفحة الأولى) ====== -->
                @include('pdf.header', [
                    'request' => $request,
                    'requestTypeName' => $requestTypeName,
                    'appLogo' => $appLogo,
                    'collegeLogo' => $collegeLogo,
                ])

                <!-- معلومات الطالب -->
                <div class="student-info">
                    @php
                        $studentName = $request->student?->name ?? $request->student?->person?->name ?? 'غير محدد';
                        $studentId = $request->student?->student_id_number ?? $request->student?->student_id ?? 'غير محدد';
                        $studentEmail = $request->student?->email ?? $request->student?->person?->email ?? 'غير محدد';
                        $studentPhone = $request->student?->phone ?? $request->student?->person?->phone ?? 'غير محدد';
                    @endphp
                    <table>
                        <tr>
                            <td><span class="label">الاسم:</span> {{ $studentName }}</td>
                            <td><span class="label">الرقم الجامعي:</span> {{ $studentId }}</td>
                        </tr>
                        <tr>
                            <td><span class="label">البريد الإلكتروني:</span> {{ $studentEmail }}</td>
                            <td><span class="label">الهاتف:</span> {{ $studentPhone }}</td>
                        </tr>
                    </table>
                </div>

                <!-- المحتوى حسب النوع -->
                <div class="content">
                    @if($requestTypeName === 'كشف علامات')
                        <h3>📊 كشف العلامات الدراسية</h3>
                        <table>
                            <thead><tr><th>#</th><th>المادة</th><th>العلامة</th><th>الحالة</th></tr></thead>
                            <tbody>
                                @forelse($grades ?? [] as $index => $grade)
                                    <tr><td>{{ $index + 1 }}</td><td>{{ $grade['subject'] ?? '' }}</td><td>{{ $grade['mark'] ?? '' }}</td><td>{{ $grade['status'] ?? '' }}</td></tr>
                                @empty
                                    <tr><td colspan="4" class="empty-message">لا توجد علامات مسجلة</td></tr>
                                @endforelse
                            </tbody>
                        </table>

                    @elseif($requestTypeName === 'حياة جامعية')
                        <h3>📚 الحياة الجامعية</h3>
                        <table>
                            <thead><tr><th>السنة</th><th>المعدل التراكمي</th><th>عدد الساعات</th><th>الحالة</th></tr></thead>
                            <tbody>
                                @forelse($academicYears ?? [] as $year)
                                    <tr><td>{{ $year['year'] ?? '' }}</td><td>{{ $year['gpa'] ?? '' }}</td><td>{{ $year['hours'] ?? '' }}</td><td>{{ $year['status'] ?? '' }}</td></tr>
                                @empty
                                    <tr><td colspan="4" class="empty-message">لا توجد سنوات دراسية مسجلة</td></tr>
                                @endforelse
                            </tbody>
                        </table>

                    @elseif($requestTypeName === 'شهادة تخرج')
                        <h3>🎓 شهادة التخرج</h3>
                        <div class="certificate-box">
                            <p style="font-size:15px;">تشهد عمادة الكلية بأن الطالب/الطالبة</p>
                            <div class="student-name">{{ $studentName }}</div>
                            <p>قد تخرج/تخرجت من كلية <strong>{{ $request->student?->college?->name ?? '' }}</strong></p>
                            <p>وذلك بتاريخ <strong>{{ $certificateData['graduation_date'] ?? now()->format('Y-m-d') }}</strong></p>
                            @if(!empty($certificateData['major'])) <p>التخصص: <strong>{{ $certificateData['major'] }}</strong></p> @endif
                            @if(!empty($certificateData['gpa'])) <p>المعدل التراكمي: <strong>{{ $certificateData['gpa'] }}</strong></p> @endif
                            @if(!empty($certificateData['grade'])) <p>التقدير: <strong>{{ $certificateData['grade'] }}</strong></p> @endif
                            <p style="margin-top:15px;font-size:12px;color:#555;">وتمنح هذه الشهادة بناءً على طلبه/ها، وتطبق عليها أحكام النظام الداخلي للكلية.</p>
                        </div>

                    @elseif($requestTypeName === 'طلب معادلة')
                        <h3>🔄 طلب معادلة مواد</h3>
                        <table>
                            <thead><tr><th>#</th><th>المادة</th><th>الجامعة المصدر</th><th>العلامة</th><th>الساعات</th></tr></thead>
                            <tbody>
                                @forelse($equivalencyData ?? [] as $index => $course)
                                    <tr><td>{{ $index + 1 }}</td><td>{{ $course['name'] ?? '' }}</td><td>{{ $course['source_university'] ?? '' }}</td><td>{{ $course['mark'] ?? '' }}</td><td>{{ $course['hours'] ?? '' }}</td></tr>
                                @empty
                                    <tr><td colspan="5" class="empty-message">لا توجد مواد للمعادلة</td></tr>
                                @endforelse
                            </tbody>
                        </table>

                    @else
                        <h3>📄 تفاصيل الطلب</h3>
                        <p><strong>نوع الطلب:</strong> {{ $requestTypeName }}</p>
                        <div class="free-text">{{ $request->reason ?? 'لا يوجد محتوى إضافي لهذا الطلب.' }}</div>
                    @endif
                </div>

                <!-- الفوتر النصي -->
                <div class="footer-text">
                    <p>تم إنشاء هذا الملف آلياً عبر النظام الرقمي للجامعات السورية</p>
                    <p>{{ now()->format('Y-m-d H:i:s') }}</p>
                </div>
            </td>
        </tr>

        <!-- ======== الصف الثاني: التوقيعات (في الأسفل) ======== -->
        <tr class="signature-row">
            <td>
                <div class="signature-wrapper">
                    <div class="signatures-title">التوقيعات</div>
                    <div class="signature-grid">
                        <table>
                            <tr>
                                @php
                                    $maxSignatures = 6;
                                @endphp
                                @for ($i = 0; $i < $maxSignatures; $i++)
                                    @php
                                        $sig = $signatures[$i] ?? null;
                                        $hasSignature = $sig && isset($sig['signature_base64']) && $sig['signature_base64'];
                                        $imageBase64 = $hasSignature ? $sig['signature_base64'] : null;
                                        $userName = $sig['user_name'] ?? '';
                                        $signedAt = isset($sig['signed_at']) ? \Carbon\Carbon::parse($sig['signed_at'])->format('Y-m-d') : '';
                                    @endphp
                                    <td class="{{ $hasSignature ? 'has-signature' : '' }}">
                                        @if($hasSignature)
                                            <div class="sig-name">{{ $userName ?: '............' }}</div>
                                            <img src="{{ $imageBase64 }}" alt="توقيع" style="width:60px; height:30px; object-fit:contain;" />
                                            @if($signedAt)
                                                <div class="sig-date">{{ $signedAt }}</div>
                                            @endif
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>