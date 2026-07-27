<?php

namespace Database\Seeders\SeedersV2;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\CollegeRepositoryInterface;
use App\Models\University;

class CollegeSeederV2 extends Seeder
{
    public function __construct(
        private CollegeRepositoryInterface $collegeRepo,
    ) {}

    public function run(): void
    {

        $colleges = [
            [
                'name' => 'كلية الهندسة المعلوماتية',
                'dean_id' => 1,
                'university_id' => 1,
            ],
            [
                'name' => 'كلية الطب البشري',
                'dean_id' => 2,
                'university_id' => 1,
            ],
            [
                'name' => 'كلية طب الأسنان',
                'dean_id' => null,
                'university_id' => 1,
            ],
            [
                'name' => 'كلية الصيدلة',
                'dean_id' => null,
                'university_id' => 1,
            ],
            [
                'name' => 'كلية الآداب ',
                'dean_id' => 3,
                'university_id' => 1,
            ],
            [
                'name' => 'كلية الاقتصاد',
                'dean_id' => null,
                'university_id' => 1,
            ],
            [
                'name' => 'كلية العلوم',
                'dean_id' => null,
                'university_id' => 1,
            ],
            [
                'name' => 'كلية الهندسة المعلوماتية',
                'dean_id' => null,
                'university_id' => 2,
            ],
            [
                'name' => 'كلية الطب البشري',
                'dean_id' => null,
                'university_id' => 2,
            ],
            [
                'name' => 'كلية طب الأسنان',
                'dean_id' => null,
                'university_id' => 2,
            ],
            [
                'name' => 'كلية الصيدلة',
                'dean_id' => null,
                'university_id' => 2,
            ],
            [
                'name' => 'كلية الآداب',
                'dean_id' => null,
                'university_id' => 2,
            ],
            [
                'name' => 'كلية الاقتصاد',
                'dean_id' => null,
                'university_id' => 2,
            ],
            [
                'name' => 'كلية الآداب',
                'dean_id' => null,
                'university_id' => 3,
            ],
            [
                'name' => 'كلية الاقتصاد',
                'dean_id' => null,
                'university_id' => 3,
            ],
            [
                'name' => 'كلية الطب البشري',
                'dean_id' => null,
                'university_id' => 4,
            ],
            [
                'name' => 'كلية طب الأسنان',
                'dean_id' => null,
                'university_id' => 4,
            ],
            [
                'name' => 'كلية الصيدلة',
                'dean_id' => null,
                'university_id' => 4,
            ],
            [
                'name' => 'كلية الهندسة المعلوماتية',
                'dean_id' => null,
                'university_id' => 5,
            ],
            [
                'name' => 'كلية الآداب',
                'dean_id' => null,
                'university_id' => 5,
            ],
            [
                'name' => 'كلية الاقتصاد',
                'dean_id' => null,
                'university_id' => 5,
            ],
            [
                'name' => 'كلية الطب البشري',
                'dean_id' => null,
                'university_id' => 6,
            ],
            [
                'name' => 'كلية الصيدلة',
                'dean_id' => null,
                'university_id' => 6,
            ],
            [
                'name' => 'كلية الآداب',
                'dean_id' => null,
                'university_id' => 6,
            ],
            [
                'name' => 'كلية الطب البيطري',
                'dean_id' => null,
                'university_id' => 7,
            ],

        ];

        foreach ($colleges as $college) {

            DB::transaction(function () use ($college) {

                $this->collegeRepo->create($college);
            });
        }
    }
}
