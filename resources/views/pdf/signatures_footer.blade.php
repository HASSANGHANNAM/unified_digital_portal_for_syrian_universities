<div style="border-top: 2px solid #2c3e50; padding-top: 8px; margin-top: 0; text-align: center; direction: rtl; font-family: 'DejaVu Sans', 'Arial', sans-serif;">
    <div style="font-size: 14px; font-weight: bold; color: #2c3e50; margin-bottom: 5px;">التوقيعات</div>
    <div class="signature-grid">
        <table style="width: 100%; border-collapse: collapse; direction: rtl;">
            <tr>
                @php
                    $maxSignatures = 6;
                @endphp
                @for ($i = 0; $i < $maxSignatures; $i++)
                    @php
                        $sig = $signatures[$i] ?? null;
                        $hasSignature = $sig && isset($sig['signature_base64']) && $sig['signature_base64'];
                        $imageBase64 = $hasSignature ? $sig['signature_base64'] : null;
                        // ✅ جلب الاسم من المصفوفة
                        $userName = $sig['user_name'] ?? '';
                        $signedAt = isset($sig['signed_at']) ? \Carbon\Carbon::parse($sig['signed_at'])->format('Y-m-d') : '';
                    @endphp
                    <td style="width: 16.66%; text-align: center; padding: 5px 4px; vertical-align: top; border: none; {{ $hasSignature && $i > 0 ? 'border-left: 1px solid #ccc; border-left-style: solid;' : '' }}">
                        @if($hasSignature && $imageBase64)
                            <div style="font-size: 10px; font-weight: bold; color: #1a1a1a; border-bottom: 1px solid #ccc; padding-bottom: 3px; margin-bottom: 4px;">
                                {{ $userName ?: '............' }}
                            </div>
                            <img src="{{ $imageBase64 }}" alt="توقيع" style="width: 60px; height: 30px; object-fit: contain; margin: 3px 0;" />
                            @if($signedAt)
                                <div style="font-size: 9px; color: #888; margin-top: 2px;">{{ $signedAt }}</div>
                            @endif
                        @endif
                    </td>
                @endfor
            </tr>
        </table>
    </div>
    <!-- النص السفلي -->
    <div style="border-top: 1px solid #dee2e6; padding-top: 8px; margin-top: 10px; font-size: 11px; color: #6c757d; text-align: center;">
        <p style="margin: 2px 0;">تم إنشاء هذا الملف آلياً عبر النظام الرقمي للجامعات السورية</p>
        <p style="margin: 2px 0;">{{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</div>