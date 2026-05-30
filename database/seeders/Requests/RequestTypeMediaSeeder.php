<?php

namespace Database\Seeders\Requests;

use Illuminate\Database\Seeder;
use App\Models\RequestType;
use App\Models\RequestTypeMedia;

class RequestTypeMediaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['request_type_name' => 'طلب تأجيل امتحان', 'name' => 'وثيقة طبية', 'type' => 'image'],
            ['request_type_name' => 'طلب تأجيل امتحان', 'name' => 'تقرير طبي', 'type' => 'pdf'],
            ['request_type_name' => 'طلب اعتذار عن فصل دراسي', 'name' => 'وثيقة سفر', 'type' => 'image'],
            ['request_type_name' => 'طلب إعادة تصحيح', 'name' => 'ورقة الامتحان', 'type' => 'pdf'],
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