<div style="direction: rtl; font-family: 'DejaVu Sans', 'Arial', sans-serif; padding: 5px 0;">

    <!-- معلومات الطالب -->
    <div style="background-color: #f8f9fa; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #e9ecef;">
        @php
            $studentName = $request->student?->name ?? $request->student?->person?->name ?? 'غير محدد';
            $studentId = $request->student?->student_id_number ?? $request->student?->student_id ?? 'غير محدد';
            $studentEmail = $request->student?->email ?? $request->student?->person?->email ?? 'غير محدد';
            $studentPhone = $request->student?->phone ?? $request->student?->person?->phone ?? 'غير محدد';
        @endphp
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 4px 8px; font-size: 13px; vertical-align: top;">
                    <strong>الاسم:</strong> {{ $studentName }}
                </td>
                <td style="padding: 4px 8px; font-size: 13px; vertical-align: top;">
                    <strong>الرقم الجامعي:</strong> {{ $studentId }}
                </td>
            </tr>
            <tr>
                <td style="padding: 4px 8px; font-size: 13px; vertical-align: top;">
                    <strong>البريد الإلكتروني:</strong> {{ $studentEmail }}
                </td>
                <td style="padding: 4px 8px; font-size: 13px; vertical-align: top;">
                    <strong>الهاتف:</strong> {{ $studentPhone }}
                </td>
            </tr>
        </table>
    </div>

    <!-- المحتوى حسب النوع -->
    <div style="min-height: 120px; margin-bottom: 20px;">

        @if($requestTypeName === 'كشف علامات')
            <h3 style="color: #2c3e50; border-right: 5px solid #3498db; padding-right: 10px; margin-bottom: 12px; font-size: 16px;">📊 كشف العلامات الدراسية</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">#</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">المادة</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">العلامة</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades ?? [] as $index => $grade)
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $index + 1 }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $grade['subject'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $grade['mark'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $grade['status'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; color: #888; padding: 15px;">لا توجد علامات مسجلة</td></tr>
                    @endforelse
                </tbody>
            </table>

        @elseif($requestTypeName === 'حياة جامعية')
            <h3 style="color: #2c3e50; border-right: 5px solid #3498db; padding-right: 10px; margin-bottom: 12px; font-size: 16px;">📚 الحياة الجامعية</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">السنة</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">المعدل التراكمي</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">عدد الساعات</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($academicYears ?? [] as $year)
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $year['year'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $year['gpa'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $year['hours'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $year['status'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align: center; color: #888; padding: 15px;">لا توجد سنوات دراسية مسجلة</td></tr>
                    @endforelse
                </tbody>
            </table>

        @elseif($requestTypeName === 'شهادة تخرج')
            <h3 style="color: #2c3e50; border-right: 5px solid #3498db; padding-right: 10px; margin-bottom: 12px; font-size: 16px;">🎓 شهادة التخرج</h3>
            <div style="text-align: center; border: 2px solid #2c3e50; border-radius: 10px; padding: 20px 15px; background: #fefefe;">
                <p style="font-size:15px;">تشهد عمادة الكلية بأن الطالب/الطالبة</p>
                <div style="font-size: 16px; font-weight: bold; color: #2980b9; margin: 8px 0;">{{ $request->student?->name ?? 'غير محدد' }}</div>
                <p>قد تخرج/تخرجت من كلية <strong>{{ $request->student?->college?->name ?? '' }}</strong></p>
                <p>وذلك بتاريخ <strong>{{ $certificateData['graduation_date'] ?? now()->format('Y-m-d') }}</strong></p>
                @if(!empty($certificateData['major']))
                    <p>التخصص: <strong>{{ $certificateData['major'] }}</strong></p>
                @endif
                @if(!empty($certificateData['gpa']))
                    <p>المعدل التراكمي: <strong>{{ $certificateData['gpa'] }}</strong></p>
                @endif
                @if(!empty($certificateData['grade']))
                    <p>التقدير: <strong>{{ $certificateData['grade'] }}</strong></p>
                @endif
                <p style="margin-top:15px;font-size:12px;color:#555;">وتمنح هذه الشهادة بناءً على طلبه/ها، وتطبق عليها أحكام النظام الداخلي للكلية.</p>
            </div>

        @elseif($requestTypeName === 'طلب معادلة')
            <h3 style="color: #2c3e50; border-right: 5px solid #3498db; padding-right: 10px; margin-bottom: 12px; font-size: 16px;">🔄 طلب معادلة مواد</h3>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">#</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">المادة</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">الجامعة المصدر</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">العلامة</th>
                        <th style="background-color: #2c3e50; color: #fff; padding: 6px 10px; text-align: center; font-size: 12px;">الساعات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equivalencyData ?? [] as $index => $course)
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $index + 1 }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $course['name'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $course['source_university'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $course['mark'] ?? '' }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 5px 10px; text-align: center; font-size: 12px;">{{ $course['hours'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align: center; color: #888; padding: 15px;">لا توجد مواد للمعادلة</td></tr>
                    @endforelse
                </tbody>
            </table>

        @else
            <h3 style="color: #2c3e50; border-right: 5px solid #3498db; padding-right: 10px; margin-bottom: 12px; font-size: 16px;">📄 تفاصيل الطلب</h3>
            <p><strong>نوع الطلب:</strong> {{ $requestTypeName }}</p>
            <div style="border: 1px solid #ced4da; padding: 12px; min-height: 80px; background: #fff; border-radius: 4px; font-size: 13px;">
                {{ $request->reason ?? 'لا يوجد محتوى إضافي لهذا الطلب.' }}
            </div>
        @endif

    </div>

</div>