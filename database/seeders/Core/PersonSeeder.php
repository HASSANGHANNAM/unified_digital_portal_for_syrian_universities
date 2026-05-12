<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Repositories\Contracts\PersonRepositoryInterface;

class PersonSeeder extends Seeder
{
    public function __construct(
        private PersonRepositoryInterface $personRepo,
    ) {}

    public function run(): void
    {
        $persons = [

            // STAFF

            [
                'national_id' => '10001',
                'full_name' => 'أحمد محمود',
                'phone' => '0999999991',
                'birth_date' => '1975-05-10',
                'national_number' => '11111111111',
                'address' => 'دمشق',
            ],

            [
                'national_id' => '10002',
                'full_name' => 'محمد علي',
                'phone' => '0999999992',
                'birth_date' => '1980-04-11',
                'national_number' => '22222222222',
                'address' => 'دمشق',
            ],

            [
                'national_id' => '10003',
                'full_name' => 'خالد يوسف',
                'phone' => '0999999993',
                'birth_date' => '1982-07-15',
                'national_number' => '33333333333',
                'address' => 'حلب',
            ],

            [
                'national_id' => '10004',
                'full_name' => 'سارة حسن',
                'phone' => '0999999994',
                'birth_date' => '1985-09-20',
                'national_number' => '44444444444',
                'address' => 'حمص',
            ],

            // STUDENTS

            [
                'national_id' => '20001',
                'full_name' => 'عمر خالد',
                'phone' => '0988888881',
                'birth_date' => '2002-03-10',
                'national_number' => '55555555555',
                'address' => 'دمشق',
            ],

            [
                'national_id' => '20002',
                'full_name' => 'سارة علي',
                'phone' => '0988888882',
                'birth_date' => '2001-11-15',
                'national_number' => '66666666666',
                'address' => 'ريف دمشق',
            ],

            [
                'national_id' => '20003',
                'full_name' => 'محمد ياسين',
                'phone' => '0988888883',
                'birth_date' => '2000-08-19',
                'national_number' => '77777777777',
                'address' => 'حلب',
            ],

            [
                'national_id' => '20004',
                'full_name' => 'ليلى حسن',
                'phone' => '0988888884',
                'birth_date' => '2001-07-06',
                'national_number' => '88888888888',
                'address' => 'اللاذقية',
            ],

            [
                'national_id' => '20005',
                'full_name' => 'علي محمود',
                'phone' => '0988888885',
                'birth_date' => '2001-02-28',
                'national_number' => '99999999999',
                'address' => 'درعا',
            ],

            [
                'national_id' => '20006',
                'full_name' => 'نور الدين أحمد',
                'phone' => '0988888886',
                'birth_date' => '2000-12-12',
                'national_number' => '10101010101',
                'address' => 'دير الزور',
            ],

            [
                'national_id' => '20007',
                'full_name' => 'فاطمة الزهراء يوسف',
                'phone' => '0988888887',
                'birth_date' => '2000-05-30',
                'national_number' => '12121212121',
                'address' => 'حماة',
            ],

            [
                'national_id' => '20008',
                'full_name' => 'عمر حسن',
                'phone' => '0988888888',
                'birth_date' => '2001-09-02',
                'national_number' => '13131313131',
                'address' => 'القنيطرة',
            ],

            [
                'national_id' => '20009',
                'full_name' => 'سارة عبد الله',
                'phone' => '0988888889',
                'birth_date' => '2002-01-20',
                'national_number' => '14141414141',
                'address' => 'حلب',
            ],

            [
                'national_id' => '20010',
                'full_name' => 'علياء محمد',
                'phone' => '0988888890',
                'birth_date' => '2001-04-12',
                'national_number' => '15151515151',
                'address' => 'حمص',
            ],

            [
                'national_id' => '20011',
                'full_name' => 'يوسف علي',
                'phone' => '0988888891',
                'birth_date' => '2001-08-09',
                'national_number' => '16161616161',
                'address' => 'السويداء',
            ],

            [
                'national_id' => '20012',
                'full_name' => 'ليلى أحمد',
                'phone' => '0988888892',
                'birth_date' => '2002-06-18',
                'national_number' => '17171717171',
                'address' => 'حماه',
            ],

        ];

        foreach ($persons as $person) {

            DB::transaction(function () use ($person) {

                $this->personRepo->create($person);

            });

        }
    }
}
