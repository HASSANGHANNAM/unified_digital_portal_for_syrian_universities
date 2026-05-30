<?php

namespace Database\Seeders\Requests;

use Illuminate\Database\Seeder;
use App\Models\Request;
use App\Models\RequestMedia;

class RequestMediaSeeder extends Seeder
{
    public function run(): void
    {
        // يجب أن يكون الطلبان 1 و 3 موجودين من RequestSeeder
        $data = [
            ['request_id' => 1, 'name' => 'تقرير_طبي.pdf', 'type' => 'pdf'],
            ['request_id' => 3, 'name' => 'ورقة_الامتحان_ممسوحة.jpg', 'type' => 'image'],
        ];

        foreach ($data as $item) {
            $request = Request::find($item['request_id']);
            if ($request) {
                RequestMedia::firstOrCreate([
                    'request_id' => $item['request_id'],
                    'name' => $item['name'],
                ], [
                    'type' => $item['type'],
                ]);
            }
        }
    }
}