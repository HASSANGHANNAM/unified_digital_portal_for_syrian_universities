<?php

namespace Database\Seeders\Academic;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StaffRepositoryInterface;
use App\Models\Department;
use App\Models\Person;
use App\Models\Staff;

class StaffSeeder extends Seeder
{
    public function __construct(
        private StaffRepositoryInterface $staffRepo,
    ) {}

    public function run(): void
    {
        // -------------------- 1. البيانات القديمة (العشوائية) --------------------
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
            // التحقق من عدم وجود موظف لهذا الشخص مسبقاً (لتجنب التكرار مع البيانات الجديدة)
            $exists = Staff::where('person_id', $person->id)->exists();
            if (!$exists) {
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

        // -------------------- 2. البيانات الجديدة من dummyData.ts --------------------
        // الموظفون المطلوبون حسب dummyData (تم ربطهم بأشخاص حقيقيين)
        $newStaffData = [
            [
                'national_id' => '01012345689', // هبة الله مصطفى (person_id = 12)
                'department_name' => 'الشؤون الإدارية',
                'hire_date' => '2015-03-01',
                'employment_status' => 'active',
                'staff_id_number' => 'STF-12-001', // يمكنك توليده بشكل تلقائي
            ],
            [
                'national_id' => '10001', // أحمد محمود (موجود مسبقاً)
                'department_name' => 'شؤون الطلاب',
                'hire_date' => '2018-09-15',
                'employment_status' => 'active',
                'staff_id_number' => 'STF-10001-001',
            ],
            [
                'national_id' => '10002', // محمد علي
                'department_name' => 'الموارد البشرية',
                'hire_date' => '2010-05-20',
                'employment_status' => 'inactive',
                'staff_id_number' => 'STF-10002-001',
            ],
        ];

        foreach ($newStaffData as $staffData) {
            // جلب الشخص عبر national_id
            $person = Person::where('national_id', $staffData['national_id'])->first();
            if (!$person) {
                // لو لم يوجد الشخص (لا يجب أن يحدث) يمكن تخطي أو إنشاء الشخص، لكننا نتأكد من وجوده
                continue;
            }

            // التحقق من عدم وجود موظف لهذا الشخص مسبقاً
            $exists = Staff::where('person_id', $person->id)->exists();
            if ($exists) {
                continue; // موجود لا نكرره
            }

            // محاولة ربط department_name بقسم حقيقي في قاعدة البيانات
            // إذا لم يوجد القسم، نترك department_id = null (أو يمكنك إنشاؤه مباشرة)
            $department = Department::where('name', $staffData['department_name'])->first();
            $departmentId = $department ? $department->id : null;

            DB::transaction(function () use ($person, $staffData, $departmentId) {
                $this->staffRepo->create([
                    'staff_id_number' => $staffData['staff_id_number'],
                    'department_id' => $departmentId,
                    'hire_date' => $staffData['hire_date'],
                    'employment_status' => $staffData['employment_status'],
                    'person_id' => $person->id,
                ]);
            });
        }
    }
}
