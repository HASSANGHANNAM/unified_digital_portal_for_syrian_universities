<?php

namespace Database\Seeders\SeedersV2;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\UniversityRepositoryInterface;

class UniversitySeederV2 extends Seeder
{
    public function __construct(
        private UniversityRepositoryInterface $universityRepo,
    ) {}
    // private/logos/{university_id}/{uuid}.png
    public function run(): void
    {
        $universities = [
            [
                'name' => 'جامعة دمشق',
                'logo_path' => 'private/logos/1/8741ec1f-8b54-4dab-95c2-f88d0aa626ed.png',
                'address' => 'دمشق',
                'accreditation' => 'حكومي',
                'university_director_id' => null,
            ],
            [
                'name' => 'جامعة حمص',
                'logo_path' => 'private/logos/2/079b145e-23a4-4f59-988b-a467059c8dd4.jpeg',
                'address' => 'حمص',
                'accreditation' => 'حكومي',
                'university_director_id' => null,
            ],
            [
                'name' => 'جامعة حلب',
                'logo_path' => 'private/logos/3/8741ec1f-8b54-4dab-95c2-f88d0aa626ed.jpeg',
                'address' => 'حلب',
                'accreditation' => 'حكومي',
                'university_director_id' => null,
            ],
            [
                'name' => 'جامعة طرطوس',
                'logo_path' => 'private/logos/4/8741ec1f-8b54-4dab-95c2-f88d0aa626ed.jpeg',
                'address' => 'طرطورس',
                'accreditation' => 'حكومي',
                'university_director_id' => null,
            ],
            [
                'name' => 'جامعة اللاذقية',
                'logo_path' => 'private/logos/5/8741ec1f-8b54-4dab-95c2-f88d0aa626ed.jpeg',
                'address' => 'اللاذقية',
                'accreditation' => 'حكومي',
                'university_director_id' => null,
            ],
            [
                'name' => 'جامعة حماة',
                'logo_path' => 'private/logos/6/8741ec1f-8b54-4dab-95c2-f88d0aa626ed.jpeg',
                'address' => 'حماة',
                'accreditation' => 'حكومي',
                'university_director_id' => null,
            ],
            [
                'name' => 'جامعة الفرات',
                'logo_path' => 'private/logos/7/8741ec1f-8b54-4dab-95c2-f88d0aa626ed.jpeg',
                'address' => 'ديرالزور',
                'accreditation' => 'حكومي',
                'university_director_id' => null,
            ],
        ];

        foreach ($universities as $university) {
            DB::transaction(function () use ($university) {
                $this->universityRepo->create($university);
            });
        }
    }
}
