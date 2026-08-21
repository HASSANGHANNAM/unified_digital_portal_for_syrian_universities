<?php

namespace Database\Seeders\Requests;

use Illuminate\Database\Seeder;
use App\Models\RequestType;
use App\Models\RequestTypeMedia;

class RequestTypeMediaSeeder extends Seeder
{
    public function run(): void
    {
        $data =
            [
                // 1. حياة جامعية أو تسلسل دراسي أو بيان وضع
                ['request_type_name' => 'حياة جامعية أو تسلسل دراسي أو بيان وضع', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'حياة جامعية أو تسلسل دراسي أو بيان وضع', 'name' => 'تبرع بالدم', 'type' => 'image'],
                ['request_type_name' => 'حياة جامعية أو تسلسل دراسي أو بيان وضع', 'name' => 'بطاقة جامعية', 'type' => 'image'],

                // 2. وثيقة دوام
                ['request_type_name' => 'وثيقة دوام', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'وثيقة دوام', 'name' => 'تبرع بالدم', 'type' => 'image'],

                // 3. كشف علامات
                ['request_type_name' => 'كشف علامات', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'كشف علامات', 'name' => 'تبرع بالدم', 'type' => 'image'],

                // 4. إيقاف تسجيل
                ['request_type_name' => 'إيقاف تسجيل', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'إيقاف تسجيل', 'name' => 'تبرع بالدم', 'type' => 'image'],

                // 5. التحويل المماثل
                ['request_type_name' => 'التحويل المماثل', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'التحويل المماثل', 'name' => 'تبرع بالدم', 'type' => 'image'],

                // 6. مصدقة تخرج أو إشعار تخرج
                ['request_type_name' => 'مصدقة تخرج أو إشعار تخرج', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'مصدقة تخرج أو إشعار تخرج', 'name' => 'تبرع بالدم', 'type' => 'image'],

                // 7. بدل تالف بطاقة جامعة
                ['request_type_name' => 'بدل تالف بطاقة جامعة', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'بدل تالف بطاقة جامعة', 'name' => 'تبرع بالدم', 'type' => 'image'],

                // 8. بدل ضائع بطاقة جامعية
                ['request_type_name' => 'بدل ضائع بطاقة جامعية', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'بدل ضائع بطاقة جامعية', 'name' => 'تبرع بالدم', 'type' => 'image'],

                // 9. إعادة عملي
                ['request_type_name' => 'إعادة عملي', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'إعادة عملي', 'name' => 'تبرع بالدم', 'type' => 'image'],

                // 10. اعتراض على علامة
                ['request_type_name' => 'اعتراض على علامة', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'اعتراض على علامة', 'name' => 'تبرع بالدم', 'type' => 'image'],

                // 11. شهادة تخرج
                ['request_type_name' => 'شهادة تخرج', 'name' => 'صورة هوية', 'type' => 'image'],
                ['request_type_name' => 'شهادة تخرج', 'name' => 'تبرع بالدم', 'type' => 'image'],

            ];

        foreach ($data as $item) {
            $requestType = RequestType::where('name', $item['request_type_name'])->first();
            if ($requestType) {
                RequestTypeMedia::firstOrCreate([
                    'request_type_id' => $requestType->id,
                    'name' => $item['name'],
                ], [
                    'type' => $item['type'],
                ]);
            }
        }
    }
}
