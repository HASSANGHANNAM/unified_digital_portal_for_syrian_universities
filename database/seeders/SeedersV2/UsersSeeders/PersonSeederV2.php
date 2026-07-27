<?php


namespace Database\Seeders\SeedersV2\UsersSeeders;

use Illuminate\Support\Facades\DB;

use App\Repositories\Contracts\PersonRepositoryInterface;
use Illuminate\Database\Seeder;

class PersonSeederV2 extends Seeder
{
    public function __construct(
        private PersonRepositoryInterface $personRepo,
    ) {}

    public function run(): void
    {
        $persons = [
            [
                // 'id'=> 1,
                'national_id' => '03180073382',
                'full_name' => 'نور الدين أحمد',
                'phone' => '0934596954',
                'birth_date' => '1985-12-12',
                'national_number' => '10101010101',
                'address' => 'دير الزور',
                'profile_image' => 'https://example.com/images/nour.jpg',
            ],
            [
                // 'id'=>2,
                'national_id' => '01012345679',
                'full_name' => 'هود محمد',
                'phone' => '0936123457',
                'birth_date' => '1999-07-22',
                'national_number' => '01012345679',
                'address' => 'دمشق، أبو رمانة، شارع الجلاء',
                'profile_image' => 'https://example.com/images/fatima.jpg',
            ],

            [
                // 'id'=> 3,

                'national_id' => '01012345680',
                'full_name' => 'يوسف سامر الحموي',
                'phone' => '0937123458',
                'birth_date' => '2000-03-10',
                'national_number' => '01012345680',
                'address' => 'دمشق، البرامكة، خلف الجامعة',
                'profile_image' => 'https://example.com/images/youssef.jpg',
            ],
            [
                // 'id'=> 4,

                'national_id' => '01012345681',
                'full_name' => 'نورا علي حسين',
                'phone' => '0938123459',
                'birth_date' => '1997-11-30',
                'national_number' => '01012345681',
                'address' => 'دمشق، المهاجرين، شارع بغداد',
                'profile_image' => 'https://example.com/images/nora.jpg',
            ],
            [
                // 'id'=> 5,

                'national_id' => '01012345682',
                'full_name' => 'محمد نور الدين',
                'phone' => '0939123460',
                'birth_date' => '1985-02-14',
                'national_number' => '01012345682',
                'address' => 'دمشق، المالكي، ساحة عرنوس',
                'profile_image' => 'https://example.com/images/mohamed.jpg',
            ],
            [
                // 'id'=> 6,

                'national_id' => '01012345683',
                'full_name' => 'سلمى عبد الرحمن',
                'phone' => '0930123461',
                'birth_date' => '1990-09-05',
                'national_number' => '01012345683',
                'address' => 'دمشق، كفر سوسة، شارع 8 آذار',
                'profile_image' => 'https://example.com/images/salmi.jpg',
            ],
            [
                // 'id'=> 7,

                'national_id' => '01012345684',
                'full_name' => 'خالد وليد السيد',
                'phone' => '0931123462',
                'birth_date' => '1982-01-18',
                'national_number' => '01012345684',
                'address' => 'دمشق، ركن الدين، شارع ابن النفيس',
                'profile_image' => 'https://example.com/images/khaled.jpg',
            ],
            [
                // 'id'=> 8,

                'national_id' => '01012345685',
                'full_name' => 'رنا باسم العقاد',
                'phone' => '0932123463',
                'birth_date' => '1995-06-23',
                'national_number' => '01012345685',
                'address' => 'دمشق، القنوات، شارع الثورة',
                'profile_image' => 'https://example.com/images/rana.jpg',
            ],
            [
                // 'id'=> 9,
                'national_id' => '01012345686',
                'full_name' => 'عمار حسام الخطيب',
                'phone' => '0933123464',
                'birth_date' => '1988-12-11',
                'national_number' => '01012345686',
                'address' => 'دمشق، الميدان، شارع خالد بن الوليد',
                'profile_image' => 'https://example.com/images/ammar.jpg',
            ],
            [
                // 'id'=> 10,
                'national_id' => '01012345687',
                'full_name' => 'لينا جمال عزام',
                'phone' => '0934123465',
                'birth_date' => '2001-04-17',
                'national_number' => '01012345687',
                'address' => 'دمشق، الشعلان، شارع الحمرا',
                'profile_image' => 'https://example.com/images/lena.jpg',
            ],
            [
                // 'id'=> 11,
                'national_id' => '01012345688',
                'full_name' => 'رامي عدنان الخطيب',
                'phone' => '0935123466',
                'birth_date' => '1996-10-03',
                'national_number' => '01012345688',
                'address' => 'دمشق، التجارة، شارع الحمرا',
                'profile_image' => 'https://example.com/images/ramy.jpg',
            ],
            [
                // 'id'=> 12,
                'national_id' => '01012345689',
                'full_name' => 'هبة الله مصطفى',
                'phone' => '0936123467',
                'birth_date' => '1992-08-19',
                'national_number' => '01012345689',
                'address' => 'دمشق، القدم، شارع فلسطين',
                'profile_image' => 'https://example.com/images/habib.jpg',
            ],
            [
                // 'id'=> 13,
                'national_id' => '01012345690',
                'full_name' => 'سامر فؤاد العبد',
                'phone' => '0937123468',
                'birth_date' => '1987-05-27',
                'national_number' => '01012345690',
                'address' => 'دمشق، دمر， مشروع دمر',
                'profile_image' => 'https://example.com/images/samer.jpg',
            ],
            [
                // 'id'=> 14,
                'national_id' => '01012345691',
                'full_name' => 'دعاء إبراهيم الشيخ',
                'phone' => '0938123469',
                'birth_date' => '1994-02-09',
                'national_number' => '01012345691',
                'address' => 'دمشق، الصالحية، شارع العابد',
                'profile_image' => 'https://example.com/images/dalia.jpg',
            ],
            [
                // 'id'=> 15,
                'national_id' => '01012345692',
                'full_name' => 'حسام تيسير الحلبي',
                'phone' => '0939123470',
                'birth_date' => '1983-11-21',
                'national_number' => '01012345692',
                'address' => 'دمشق، برزة، شارع البلدية',
                'profile_image' => 'https://example.com/images/husam.jpg',
            ],
            [
                // 'id'=> 16,
                'national_id' => '01012345693',
                'full_name' => 'ميساء أنور حمود',
                'phone' => '0930123471',
                'birth_date' => '1998-07-14',
                'national_number' => '01012345693',
                'address' => 'دمشق، القصور، شارع فايز منصور',
                'profile_image' => 'https://example.com/images/maysa.jpg',
            ],
            [
                // 'id'=> 17,
                'national_id' => '01012345694',
                'full_name' => 'باسل أكرم النوري',
                'phone' => '0931123472',
                'birth_date' => '2002-01-06',
                'national_number' => '01012345694',
                'address' => 'دمشق، العفيف، شارع خالد بن الوليد',
                'profile_image' => 'https://example.com/images/basel.jpg',
            ],
            [
                // 'id'=> 18,
                'national_id' => '01012345695',
                'full_name' => 'ريم جورج الخوري',
                'phone' => '0932123473',
                'birth_date' => '1991-09-28',
                'national_number' => '01012345695',
                'address' => 'دمشق، باب توما، شارع المستشفى',
                'profile_image' => 'https://example.com/images/rima.jpg',
            ],
            [
                // 'id'=> 19,
                'national_id' => '01012345696',
                'full_name' => 'غسان نبيل الحافظ',
                'phone' => '0933123474',
                'birth_date' => '1980-04-15',
                'national_number' => '01012345696',
                'address' => 'دمشق، الشاغور، شارع النصر',
                'profile_image' => 'https://example.com/images/gasan.jpg',
            ],
            [
                // 'id'=> 20,
                'national_id' => '01012345697',
                'full_name' => 'نانسي رائف صالح',
                'phone' => '0934123475',
                'birth_date' => '1993-12-02',
                'national_number' => '01012345697',
                'address' => 'دمشق، الحمرا، شارع المهدي بن بركة',
                'profile_image' => 'https://example.com/images/nancy.jpg',
            ],

            // STAFF

            [
                // 'id'=> 21,
                'national_id' => '10001',
                'full_name' => 'أحمد محمود',
                'phone' => '0999999991',
                'birth_date' => '1975-05-10',
                'national_number' => '11111111111',
                'address' => 'دمشق',
                'profile_image' => 'https://example.com/images/ahmad.jpg',
            ],

            [
                // 'id'=> 22,
                'national_id' => '10002',
                'full_name' => 'محمد علي',
                'phone' => '0999999992',
                'birth_date' => '1980-04-11',
                'national_number' => '22222222222',
                'address' => 'دمشق',
                'profile_image' => 'https://example.com/images/mohamed.jpg',
            ],

            [
                // 'id'=> 23,
                'national_id' => '10003',
                'full_name' => 'خالد يوسف',
                'phone' => '0999999993',
                'birth_date' => '1982-07-15',
                'national_number' => '33333333333',
                'address' => 'حلب',
                'profile_image' => 'https://example.com/images/khaled.jpg',
            ],

            [
                // 'id'=> 24,
                'national_id' => '10004',
                'full_name' => 'سارة حسن',
                'phone' => '0999999994',
                'birth_date' => '1985-09-20',
                'national_number' => '44444444444',
                'address' => 'حمص',
                'profile_image' => 'https://example.com/images/sarah.jpg',
            ],

            // STUDENTS

            [
                // 'id'=> 25,
                'national_id' => '20001',
                'full_name' => 'عمر خالد',
                'phone' => '0988888881',
                'birth_date' => '2002-03-10',
                'national_number' => '55555555555',
                'address' => 'دمشق',
                'profile_image' => 'https://example.com/images/omar.jpg',
            ],

            [
                // 'id'=> 26,
                'national_id' => '20002',
                'full_name' => 'سارة علي',
                'phone' => '0988888882',
                'birth_date' => '2001-11-15',
                'national_number' => '66666666666',
                'address' => 'ريف دمشق',
                'profile_image' => 'https://example.com/images/sarah.jpg',
            ],

            [
                // 'id'=> 27,
                'national_id' => '20003',
                'full_name' => 'محمد ياسين',
                'phone' => '0988888883',
                'birth_date' => '2000-08-19',
                'national_number' => '77777777777',
                'address' => 'حلب',
                'profile_image' => 'https://example.com/images/mohamed.jpg',
            ],

            [
                // 'id'=> 28,
                'national_id' => '20004',
                'full_name' => 'ليلى حسن',
                'phone' => '0988888884',
                'birth_date' => '2001-07-06',
                'national_number' => '88888888888',
                'address' => 'اللاذقية',
                'profile_image' => 'https://example.com/images/layla.jpg',
            ],

            [
                // 'id'=> 29,
                'national_id' => '20005',
                'full_name' => 'علي محمود',
                'phone' => '0988888885',
                'birth_date' => '2001-02-28',
                'national_number' => '99999999999',
                'address' => 'درعا',
                'profile_image' => 'https://example.com/images/ali.jpg',
            ],

            [
                // 'id'=> 30,
                'national_id' => '01012345678',
                'full_name' => 'أحمد محمد العلي',
                'phone' => '0935123456',
                'birth_date' => '1998-05-15',
                'national_number' => '01012345678',
                'address' => 'دمشق، المزة، شارع الشيخ سعد',
                'profile_image' => 'https://example.com/images/ahmad.jpg',
            ],

            [
                // 'id'=> 31,
                'national_id' => '20007',
                'full_name' => 'فاطمة الزهراء يوسف',
                'phone' => '0988888887',
                'birth_date' => '2000-05-30',
                'national_number' => '12121212121',
                'address' => 'حماة',
                'profile_image' => 'https://example.com/images/fatima.jpg',
            ],

            [
                // 'id'=> 32,
                'national_id' => '20008',
                'full_name' => 'عمر حسن',
                'phone' => '0988888888',
                'birth_date' => '2001-09-02',
                'national_number' => '13131313131',
                'address' => 'القنيطرة',
                'profile_image' => 'https://example.com/images/omar.jpg',
            ],

            [
                // 'id'=> 33,
                'national_id' => '20009',
                'full_name' => 'سارة عبد الله',
                'phone' => '0988888889',
                'birth_date' => '2002-01-20',
                'national_number' => '14141414141',
                'address' => 'حلب',
                'profile_image' => 'https://example.com/images/sarah.jpg',
            ],

            [
                // 'id'=> 34,
                'national_id' => '20010',
                'full_name' => 'علياء محمد',
                'phone' => '0988888890',
                'birth_date' => '2001-04-12',
                'national_number' => '15151515151',
                'address' => 'حمص',
                'profile_image' => 'https://example.com/images/aliya.jpg',
            ],

            [
                // 'id'=> 35,
                'national_id' => '20011',
                'full_name' => 'يوسف علي',
                'phone' => '0988888891',
                'birth_date' => '2001-08-09',
                'national_number' => '16161616161',
                'address' => 'السويداء',
                'profile_image' => 'https://example.com/images/youssef.jpg',
            ],

            [
                // 'id'=> 36,
                'national_id' => '20012',
                'full_name' => 'ليلى أحمد',
                'phone' => '0988888892',
                'birth_date' => '2002-06-18',
                'national_number' => '17171717171',
                'address' => 'حماه',
                'profile_image' => 'https://example.com/images/layla.jpg',
            ],
            [
                // 'id'=>37,

                'national_id' => '01012345679',
                'full_name' => 'فاطمة خالد الحسين',
                'phone' => '0936123457',
                'birth_date' => '1999-07-22',
                'national_number' => '01012345679',
                'address' => 'دمشق، أبو رمانة، شارع الجلاء',
                'profile_image' => 'https://example.com/images/fatima.jpg',
            ],

        ];

        foreach ($persons as $person) {

            DB::transaction(function () use ($person) {

                $this->personRepo->create($person);
            });
        }
    }
}
