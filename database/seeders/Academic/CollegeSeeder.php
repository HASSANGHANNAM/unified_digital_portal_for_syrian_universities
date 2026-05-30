<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\CollegeRepositoryInterface;
use App\Models\University;

class CollegeSeeder extends Seeder
{
    public function __construct(
        private CollegeRepositoryInterface $collegeRepo,
    ) {}

    public function run(): void
    {
        $damascusUniversity = University::where('name', 'جامعة دمشق')->first();
        $aleppoUniversity = University::where('name', 'جامعة حلب')->first();
        $homssUniversity = University::where('name', 'جامعة حمص')->first();

        $colleges = [

            [
                'name' => 'كلية الهندسة المعلوماتية',
                'university_id' => $damascusUniversity->id,
            ],

            [
                'name' => 'كلية الطب البشري',
                'university_id' => $damascusUniversity->id,
            ],

            [
                'name' => 'كلية الصيدلة',
                'university_id' => $damascusUniversity->id,
            ],

            [
                'name' => 'كلية الهندسة المعلوماتية',
                'university_id' => $aleppoUniversity->id,
            ],

            [
                'name' => 'كلية الطب البشري',
                'university_id' => $aleppoUniversity->id,
            ],

            [
                'name' => 'كلية الهندسة المعلوماتية',
                'university_id' => $homssUniversity->id,
            ],

            [
                'name' => 'كلية العلوم',
                'university_id' => $homssUniversity->id,
            ],
            [
                'name' => 'كلية العلوم',
                'university_id' => $damascusUniversity->id,
            ],
            [
                'name' => 'كلية الآداب والعلوم الإنسانية',
                'university_id' => $damascusUniversity->id,
            ],
            [
                'name' => 'كلية الاقتصاد',
                'university_id' => $damascusUniversity->id,
            ],

        ];

        foreach ($colleges as $college) {

            DB::transaction(function () use ($college) {

                $this->collegeRepo->create($college);
            });
        }
    }
}
