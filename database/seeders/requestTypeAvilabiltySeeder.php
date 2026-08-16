<?php

namespace Database\Seeders;

use App\Repositories\Contracts\RequestTypeAvailabilityRepositoryInterface;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class requestTypeAvilabiltySeeder extends Seeder
{
    public function __construct(
        private RequestTypeAvailabilityRepositoryInterface $availabilityRepo,
    ) {}

    public function run(): void
    {
        $availability = [
            [
                'is_available' => true,
                'request_type_id' => 1,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 2,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 3,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 4,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 5,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 6,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 7,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 8,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 9,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 10,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 11,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 12,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 13,
                'college_id' => 1,
            ],
            [
                'is_available' => true,
                'request_type_id' => 14,
                'college_id' => 1,
            ],
        ];

        foreach ($availability as $av) {

            DB::transaction(function () use ($av) {

                $this->availabilityRepo->create($av);
            });
        }
    }
}
