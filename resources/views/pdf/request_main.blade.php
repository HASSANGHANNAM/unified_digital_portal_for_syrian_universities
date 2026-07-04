<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب {{ $requestTypeName }}</title>
    <style>
        /* ====================================================== */
        /*                     الإعدادات العامة                    */
        /* ====================================================== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            direction: rtl;
            padding: 20px;
            background: #fff;
            color: #1a1a1a;
            font-size: 14px;
            line-height: 1.6;
        }

        /* ====================================================== */
        /*                  معلومات الطالب (ثابت)                 */
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
        /*              المحتوى المتغير (حسب النوع)               */
        /* ====================================================== */
        .content {
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
        /*                   الفوتر النصي (ثابت)                  */
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
    </style>
</head>
<body>

    <!-- ========================================================= -->
    <!-- ====== استدعاء الهيدر من ملف منفصل (يظهر في الصفحة الأولى) ====== -->
    <!-- ========================================================= -->
    @include('pdf.header', [
        'request' => $request,
        'requestTypeName' => $requestTypeName,
        'appLogo' => $appLogo,
        'collegeLogo' => $collegeLogo,
    ])

    <!-- ========================================================= -->
    <!-- ============== معلومات الطالب (بدون بريد إلكتروني) ===== -->
    <!-- ========================================================= -->
    <div class="student-info">
        @php
            $student = $request->student;
            $person = $student?->person;

            $fullName = $person?->full_name ?? 'غير محدد';
            $studentIdNumber = $student?->student_id_number ?? 'غير محدد';
            $major = $student?->major ?? 'غير محدد';

            $currentYearNumber = $student?->current_year;
            $yearMap = [
                1 => 'الأولى',
                2 => 'الثانية',
                3 => 'الثالثة',
                4 => 'الرابعة',
                5 => 'الخامسة',
                6 => 'السادسة',
            ];
            $currentYear = $yearMap[$currentYearNumber] ?? ($currentYearNumber ?: 'غير محدد');

            $birthDate = $person?->birth_date ? \Carbon\Carbon::parse($person->birth_date)->format('Y-m-d') : 'غير محدد';
            $phone = $person?->phone ?? 'غير محدد';
        @endphp
        <table>
            <tr>
                <td><span class="label">الاسم الكامل:</span> {{ $fullName }}</td>
                <td><span class="label">الرقم الجامعي:</span> {{ $studentIdNumber }}</td>
            </tr>
            <tr>
                <td><span class="label">القسم:</span> {{ $major }}</td>
                <td><span class="label">السنة:</span> {{ $currentYear }}</td>
            </tr>
            <tr>
                <td><span class="label">تاريخ الميلاد:</span> {{ $birthDate }}</td>
                <td><span class="label">الهاتف:</span> {{ $phone }}</td>
            </tr>
        </table>
    </div>

    <!-- ========================================================= -->
    <!-- ============= المحتوى (يتغير حسب النوع) ================ -->
    <!-- ========================================================= -->
    <div class="content">

        @if(str_contains($requestTypeName, 'كشف علامات'))
            <!-- ====== عنوان العلامات (في المنتصف) ====== -->
            <h3 style="text-align: center;"> كشف العلامات الدراسية</h3>
            <!-- ====== جدول العلامات ====== -->
            <table>
                <thead>
                    <tr>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">#</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">المادة</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">الرمز</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">العلامة</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades['courses'] ?? [] as $index => $course)
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $index + 1 }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $course['course_name'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $course['code'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $course['total'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">
                                @if(($course['status'] ?? '') === 'passed')
                                    <span style="color: green; font-weight: bold;">ناجح</span>
                                @elseif(($course['status'] ?? '') === 'passed_with_assistance')
                                    <span style="color: #e67e22; font-weight: bold;">ناجح بالمساعدة</span>
                                @elseif(($course['status'] ?? '') === 'failed')
                                    <span style="color: red; font-weight: bold;">راسب</span>
                                @else
                                    {{ $course['status'] ?? '' }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; color: #888; padding: 15px;">لا توجد علامات مسجلة</td></tr>
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
                <div class="student-name">{{ $fullName }}</div>
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

</body>
</html>