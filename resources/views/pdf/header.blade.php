<!-- ========================================================= -->
<!-- ===================== الهيدر (3 أقسام) =================== -->
<!-- ========================================================= -->
<table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0; direction: rtl;">
    <tr>
        <!-- ====== العمود الأيسر: شعار الجامعة ====== -->
        <td style="text-align: left; width: 16.66%; vertical-align: middle; padding: 0; margin: 0;">
            @if($collegeLogo)
                <img src="{{ $collegeLogo }}" alt="شعار الكلية" style="width:auto; height:80px; object-fit:contain; display:inline-block; margin:0; padding:0;" />
            @endif
        </td>

        <!-- ====== العمود الأوسط: المعلومات ====== -->
        <td style="text-align: center; width: 66.68%; vertical-align: middle; padding: 0; margin: 0;">
            @php
                $universityName = $request->student?->college?->university?->name ?? '';
                $collegeName = $request->student?->college?->name ?? '';
                $institutionName = trim($universityName . ' - ' . $collegeName);
                $institutionName = trim($institutionName, ' - ');
            @endphp

            @if($institutionName)
                <div style="font-size: 14px; font-weight: bold; color: #010102; line-height: 1.2; margin:0; padding:0;">
                    {{ $institutionName }}
                </div>
            @endif

            <div style="font-size: 16px; font-weight: bold; color: #010102; line-height: 1.2; margin:0; padding:0;">
                {{ $requestTypeName }}
            </div>
            <div style="font-size: 11px; color: #010102; margin:0; padding:0;">
                <span>رقم الطلب: {{ $request->id }}</span>
                <span>|</span>
                <span>التاريخ: {{ now()->format('Y-m-d') }}</span>
            </div>
        </td>

        <!-- ====== العمود الأيمن: شعار التطبيق ====== -->
        <td style="text-align: right; width: 16.66%; vertical-align: middle; padding: 0; margin: 0;">
            @if($appLogo)
                <img src="{{ $appLogo }}" alt="شعار التطبيق" style="width:80px; height:80px; object-fit:contain; display:inline-block; margin:0; padding:0;" />
            @endif
        </td>
    </tr>
</table>

<!-- ====== خط فاصل أسفل الهيدر ====== -->
<div style="border-bottom: 2px solid #2c3e50; margin: 0; padding: 0; width: 100%;"></div>