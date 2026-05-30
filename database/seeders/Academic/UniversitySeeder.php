<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\UniversityRepositoryInterface;
use App\Models\University;
use App\Models\Person;

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
                'accreditation' => 'حكومي',
            ],
            [
                'name' => 'جامعة حلب',
                'address' => 'حلب',
                'accreditation' => 'حكومي',
            ],
            [
                'name' => 'جامعة حمص',
                'address' => 'حمص',
                'accreditation' => 'حكومي',
            ],
        ];

        foreach ($universities as $university) {
            DB::transaction(function () use ($university) {
                $this->universityRepo->create($university);
            });
        }

        // ========== تعيين مدير جامعة دمشق (بعد إضافة الأشخاص) ==========
        // ملاحظة: لا يمكن تعيينه هنا مباشرة لأن PersonSeeder ينفذ بعد هذا السيدر.
        // لذلك سنستخدم static::class لتعيينه في وقت لاحق عبر DatabaseSeeder.
        // الحل الأفضل: وضع هذا الكود في سيدر منفصل أو في DatabaseSeeder بعد PersonSeeder.
        // لكن للتبسيط، سنقوم بتعيينه في DatabaseSeeder مباشرة بعد PersonSeeder.
        // إليك الكود الذي ستضيفه في DatabaseSeeder بعد استدعاء PersonSeeder:
        /*
        $damascus = University::where('name', 'جامعة دمشق')->first();
        $director = Person::where('national_id', '01012345690')->first(); // person_id = 13
        if ($damascus && $director) {
            $damascus->update(['university_director_id' => $director->id]);
        }
        */
    }
}
