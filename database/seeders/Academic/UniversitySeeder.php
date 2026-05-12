<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\UniversityRepositoryInterface;

class UniversitySeeder extends Seeder
{
    public function __construct(
        private UniversityRepositoryInterface $universityRepo,
    ) {}

    public function run(): void
    {
        $universities = [

            [
                'name' => 'جامعة دمشق',
                'address' => 'دمشق - البرامكة',
                'accreditation' => ' حكومي',
            ],

            [
                'name' => 'جامعة حلب',
                'address' => 'حلب',
                'accreditation' => ' حكومي',
            ],
            [
                'name' => 'جامعة حمص',
                'address' => 'حمص',
                'accreditation' => ' حكومي',
            ],

        ];

        foreach ($universities as $university) {

            DB::transaction(function () use ($university) {

                $this->universityRepo->create($university);

            });

        }
    }
}
