<?php

namespace Database\Seeders\SeedersV2;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Models\Department;
use App\Models\Person;
use App\Models\Student;

class StudentSeederV2 extends Seeder
{
    public function __construct(
        private StudentRepositoryInterface $studentRepo,
    ) {}

    public function run(): void
    {

        $students = [
            [
                'student_id_number' => '2024001',
                'academic_status'   => 'مستمر',
                'major'             => 'هندسة برمجيات',
                'enrollment_year'   => 2021,
                'current_year'      => 1,
                'current_semester'  => 1,
                'current_gpa'       => 3.5,
                'advisor_id'        => null,
                'person_id'         => 7,
                'college_id'        => 1,
                'department_id'     => 4,
            ],
            [
                'student_id_number' => '2024002',
                'academic_status'   => 'مستمر',
                'major'             => 'هندسة برمجيات',
                'enrollment_year'   => 2022,
                'current_year'      => 4,
                'current_semester'  => 1,
                'current_gpa'       => 3.7,
                'advisor_id'        => null,
                'person_id'         => 8,
                'college_id'        => 1,
                'department_id'     => 1,
            ],
            [
                'student_id_number' => '2024003',
                'academic_status'   => 'مستمر',
                'major'             => 'هندسة برمجيات',
                'enrollment_year'   => 2023,
                'current_year'      => 4,
                'current_semester'  => 1,
                'current_gpa'       => 3.2,
                'advisor_id'        => null,
                'person_id'         => 9,
                'college_id'        => 1,
                'department_id'     => 2,
            ],
            [
                'student_id_number' => '2024004',
                'academic_status'   => 'مستمر',
                'major'             => 'هندسة برمجيات',
                'enrollment_year'   => 2024,
                'current_year'      => 4,
                'current_semester'  => 1,
                'current_gpa'       => 3.8,
                'advisor_id'        => null,
                'person_id'         => 10,
                'college_id'        => 1,
                'department_id'     => 2,
            ],
            [
                'student_id_number' => '2024005',
                'academic_status'   => 'مستمر',
                'major'             => 'هندسة برمجيات',
                'enrollment_year'   => 2025,
                'current_year'      => 4,
                'current_semester'  => 1,
                'current_gpa'       => 3.0,
                'advisor_id'        => null,
                'person_id'         => 11,
                'college_id'        => 1,
                'department_id'     => 1,
            ],
            [
                'student_id_number' => '2024006',
                'academic_status'   => 'مستمر',
                'major'             => 'هندسة برمجيات',
                'enrollment_year'   => 2026,
                'current_year'      => 4,
                'current_semester'  => 1,
                'current_gpa'       => 3.9,
                'advisor_id'        => null,
                'person_id'         => 12,
                'college_id'        => 1,
                'department_id'     => 2,
            ],
            [
                'student_id_number' => '2024007',
                'academic_status'   => 'مستمر',
                'major'             => 'هندسة برمجيات',
                'enrollment_year'   => 2021,
                'current_year'      => 4,
                'current_semester'  => 1,
                'current_gpa'       => 3.4,
                'advisor_id'        => null,
                'person_id'         => 25,
                'college_id'        => 1,
                'department_id'     => 3,
            ],
        ];
        foreach ($students as $studentData) {
            DB::transaction(function () use ($studentData) {
                $person = Person::where('id', $studentData['person_id'])->first();
                if (!$person) {
                    throw new \Exception("الشخص '{$studentData['person_name']}' غير موجود");
                }
                $existing = Student::where('student_id_number', $studentData['student_id_number'])->first();
                if (!$existing) {
                    $this->studentRepo->create([
                        'student_id_number' => $studentData['student_id_number'],
                        'academic_status' => $studentData['academic_status'],
                        'major' => $studentData['major'],
                        'enrollment_year' => $studentData['enrollment_year'],
                        'current_year' => $studentData['current_year'],
                        'current_semester' => $studentData['current_semester'],
                        'current_gpa' => $studentData['current_gpa'],
                        'advisor_id' => $studentData['advisor_id'],
                        'person_id' => $studentData['person_id'],
                        'college_id' => $studentData['college_id'],
                        'department_id' => $studentData['department_id'],
                    ]);
                }
            });
        }
    }
}
