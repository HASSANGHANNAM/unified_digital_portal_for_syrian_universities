<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['name' => 'المجموعة الأولى - هندسة برمجيات'],
            ['name' => 'المجموعة الثانية - ذكاء صنعي'],
            ['name' => 'مجموعة المختبر - فيزياء'],
        ];

        foreach ($groups as $group) {
            Group::firstOrCreate(['name' => $group['name']]);
        }
    }
}