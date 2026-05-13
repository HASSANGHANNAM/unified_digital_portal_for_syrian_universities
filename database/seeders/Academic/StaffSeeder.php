<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StaffRepositoryInterface;
use App\Models\Department;
use App\Models\Person;

class StaffSeeder extends Seeder
{
    public function __construct(
        private StaffRepositoryInterface $staffRepo,
    ) {}

    public function run(): void
    {
       
        $departments = Department::whereIn('name', [
            'هندسة البرمجيات',
            'الذكاء الاصطناعي',
            'الشبكات',
            'الأمن السيبراني',
            'الجراحة العامة',
            'طب الأطفال',
            'طب النساء والتوليد',
            'الفيزياء',
            'الكيمياء',
            'الأحياء',
        ])->get();


        $persons = Person::whereIn('full_name', [
            'أحمد محمود',
            'محمد علي',
            'خالد يوسف',
            'سارة حسن',
            'ليلى عبد الله',
            'علياء محمد',
            'يوسف أحمد',
            'نور الدين خالد',
            'فاطمة الزهراء علي',
            'عمر حسن',

        ])->get();


        foreach ($persons as $person) {
            $randomDepartment = $departments->random();

            DB::transaction(function () use ($person, $randomDepartment) {
                $this->staffRepo->create([
                    'staff_id_number' => 'STF-' . $person->id . '-' . rand(1000, 9999),
                    'department_id' => $randomDepartment->id,
                    'hire_date' => now(),
                    'employment_status' => 'مثبت',
                    'person_id' => $person->id,
                ]);
            });
        }
    }
}
