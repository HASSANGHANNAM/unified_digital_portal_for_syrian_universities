<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'البوابة الرقمية الموحدة') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #ffffff;
            direction: rtl;
            padding: 20px;
        }

        /* الحاوية الرئيسية مع تدرجات زرقاء حولها */
        .container {
            background: #ffffff;
            border-radius: 32px;
            padding: 60px 50px 50px;
            max-width: 540px;
            width: 100%;
            text-align: center;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.08),
                        0 0 0 6px rgba(37, 99, 235, 0.04),
                        0 20px 60px rgba(37, 99, 235, 0.08);
            position: relative;
            transition: box-shadow 0.3s ease;
        }

        .container::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 36px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.06), rgba(37, 99, 235, 0.02), rgba(37, 99, 235, 0.06));
            z-index: -1;
            filter: blur(4px);
        }

        /* اللوغو */
        .logo {
            margin-bottom: 28px;
        }

        .logo img {
            max-width: 160px;
            height: auto;
            border-radius: 16px;
            display: block;
            margin: 0 auto;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.10);
        }

        .logo-placeholder {
            font-size: 72px;
            line-height: 1;
            display: block;
        }

        .logo-sub {
            font-size: 14px;
            color: #94a3b8;
            margin-top: 6px;
        }

        /* العنوان الرئيسي */
        h1 {
            font-size: 26px;
            font-weight: 600;
            color: #0b1e3a;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }

        .subtitle {
            font-size: 18px;
            font-weight: 400;
            color: #1e3a6f;
            margin-bottom: 6px;
        }

        .subtitle span {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            margin-top: 20px;
            background: #2563eb;
            color: #ffffff;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 22px;
            border-radius: 30px;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .divider {
            border: none;
            border-top: 1px solid #e9edf4;
            margin: 28px 0 20px;
        }

        .footer-info {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
            font-size: 13px;
            color: #94a3b8;
        }

        .footer-info span {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .footer-info .dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
            margin-left: 6px;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.9); }
        }

        @media (max-width: 480px) {
            .container {
                padding: 40px 24px 32px;
            }
            h1 {
                font-size: 22px;
            }
            .subtitle {
                font-size: 16px;
            }
            .logo img {
                max-width: 120px;
            }
        }
    </style>
</head>
<body>

    <div class="container">

        <!-- اللوغو باستخدام AppLogoTrait -->
        @php
            use App\Services\Traits\AppLogoTrait;

            // إنشاء كائن مؤقت يستخدم الـ Trait
            $logoHelper = new class {
                use AppLogoTrait;
            };

            $logoBase64 = $logoHelper->getAppLogoBase64();
            $appName = config('app.name', 'البوابة الرقمية الموحدة');
        @endphp

        <div class="logo">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" alt="{{ $appName }}">
            @else
                <span class="logo-placeholder">🏛️</span>
                <div class="logo-sub">(اللوغو غير موجود)</div>
            @endif
        </div>

        <!-- النصوص -->
        <h1>أهلاً بكم</h1>
        <div class="subtitle">
            البوابة الرقمية الموحدة <span>للجامعات السورية</span>
        </div>
        <div style="font-size: 14px; color: #64748b; margin-top: 4px;">
            back_end
        </div>

        <div class="badge">✓ النظام جاهز</div>

        <hr class="divider">

        <div class="footer-info">
            <span>
                <span class="dot"></span>
                {{ config('app.env') === 'production' ? '🚀 إنتاج' : '⚙️ تطوير' }}
            </span>
            <span>الإصدار {{ config('app.version', '1.0.0') }}</span>
        </div>

    </div>

</body>
</html>