<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Models\College;

class DepartmentSeeder extends Seeder
{
    public function __construct(
        private DepartmentRepositoryInterface $departmentRepo,
    ) {}

    public function run(): void
    {
        $informaticsCollege = College::where('name', 'كلية الهندسة المعلوماتية')->first();
        $medicineCollege = College::where('name', 'كلية الطب البشري')->first();
        $sciencesCollege = College::where('name', 'كلية العلوم')->first();

        $departments = [

            [
                'name' => 'هندسة البرمجيات',
                'college_id' => $informaticsCollege->id,
            ],

            [
                'name' => 'الذكاء الاصطناعي',
                'college_id' => $informaticsCollege->id,
            ],

            [
                'name' => 'الشبكات',
                'college_id' => $informaticsCollege->id,
            ],

            [
                'name' => 'الأمن السيبراني',
                'college_id' => $informaticsCollege->id,
            ],

            [
                'name' => 'الجراحة العامة',
                'college_id' => $medicineCollege->id,
            ],

            [
                'name' => 'طب الأطفال',
                'college_id' => $medicineCollege->id,
            ],

            [
                'name' => 'طب النساء والتوليد',
                'college_id' => $medicineCollege->id,
            ],

            [
                'name' => 'الفيزياء',
                'college_id' => $sciencesCollege->id,
            ],

            [
                'name' => 'الكيمياء',
                'college_id' => $sciencesCollege->id,
            ],

            [
                'name' => 'الأحياء',
                'college_id' => $sciencesCollege->id,
            ],

        ];

        foreach ($departments as $department) {

            DB::transaction(function () use ($department) {

                $this->departmentRepo->create($department);

            });

        }
    }
}
