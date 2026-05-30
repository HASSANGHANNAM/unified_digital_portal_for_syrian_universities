<?php

namespace Database\Seeders\Sanctions;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SanctionType;
use App\Repositories\Contracts\SanctionTypeRepositoryInterface;

class SanctionTypeSeeder extends Seeder
{
    public function __construct(
        private SanctionTypeRepositoryInterface $sanctionTypeRepo,
    ) {}

    public function run(): void
    {
        $types = [
            // ========== الأنواع الموجودة مسبقاً ==========
            [
                'name' => 'إنذار امتحاني',
                'reason' => 'استخدام وسائل غير مسموحة أثناء الامتحان',
                'years' => 0,
                'months' => 0,
                'days' => 30,
            ],
            [
                'name' => 'إنذار سلوكي',
                'reason' => 'إزعاج الآخرين أو التصرف بطريقة غير لائقة',
                'years' => 0,
                'months' => 0,
                'days' => 14,
            ],
            [
                'name' => 'إنذار غياب',
                'reason' => 'تجاوز نسبة الغياب المسموح بها',
                'years' => 0,
                'months' => 0,
                'days' => 0,
            ],
            [
                'name' => 'حرمان من مقرر',
                'reason' => 'الغش أثناء الامتحان النهائي',
                'years' => 0,
                'months' => 6,
                'days' => 0,
            ],
            [
                'name' => 'حرمان من امتحان',
                'reason' => 'عدم استكمال متطلبات المقرر',
                'years' => 0,
                'months' => 0,
                'days' => 45,
            ],
            [
                'name' => 'فصل أكاديمي مؤقت',
                'reason' => 'تكرار المخالفات الأكاديمية والسلوكية',
                'years' => 1,
                'months' => 0,
                'days' => 0,
            ],
            [
                'name' => 'فصل نهائي',
                'reason' => 'تكرار المخالفات الخطيرة بعد الفصل المؤقت',
                'years' => 0,
                'months' => 0,
                'days' => 0,
            ],
            [
                'name' => 'إنذار تأخر',
                'reason' => 'تأخر عن تسليم المشاريع أو الواجبات',
                'years' => 0,
                'months' => 0,
                'days' => 0,
            ],
            [
                'name' => 'عقوبة انتحال',
                'reason' => 'انتحال أعمال أو بحوث من مصادر أخرى',
                'years' => 0,
                'months' => 1,
                'days' => 0,
            ],
            [
                'name' => 'إنذار مختبرات',
                'reason' => 'عدم الالتزام بإجراءات السلامة في المختبر',
                'years' => 0,
                'months' => 0,
                'days' => 0,
            ],
            [
                'name' => 'عقوبة سرقة علمية',
                'reason' => 'نسخ كامل مشروع تخرج من طالب سابق',
                'years' => 0,
                'months' => 3,
                'days' => 0,
            ],
            [
                'name' => 'إنذار تحضير',
                'reason' => 'إهمال التحضير اليومي والواجبات المنزلية',
                'years' => 0,
                'months' => 0,
                'days' => 0,
            ],
            [
                'name' => 'عقوبة إلكترونية',
                'reason' => 'استخدام الهاتف أثناء الامتحان الإلكتروني',
                'years' => 0,
                'months' => 0,
                'days' => 0,
            ],

            // ========== الأنواع الجديدة من dummyData.ts ==========
            [
                'name' => 'إنذار كتابي',
                'reason' => 'الغياب المتكرر',
                'years' => 0,
                'months' => 3,
                'days' => 0,
            ],
            [
                'name' => 'فصل مؤقت',
                'reason' => 'الغش في الامتحان',
                'years' => 0,
                'months' => 6,
                'days' => 0,
            ],
            [
                'name' => 'حرمان من التقدم للامتحانات',
                'reason' => 'سلوك غير لائق',
                'years' => 0,
                'months' => 2,
                'days' => 15,
            ],
        ];

        foreach ($types as $type) {
            DB::transaction(function () use ($type) {
                SanctionType::firstOrCreate(
                    ['name' => $type['name']],
                    $type
                );
            });
        }
    }
}
