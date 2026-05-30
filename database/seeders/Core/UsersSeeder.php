<?php

namespace Database\Seeders\Core;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function __construct(
        private UserRepositoryInterface $userRepo,
    ) {}

    public function run(): void
    {

        $users = [
            [
                'username' => 'dean.ahmed',
                'email' => 'dean@university.edu',
                'password' => 'Dean@123456',
                'status' => 'inactive',
                'email_verified_at' => now(),
                'role' => 'Dean',
            ],
            [
                'username' => 'hod.mohammad',
                'email' => 'hod@university.edu',
                'password' => 'Hod@123456',
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'HeadOfDepartment',
            ],
            [
                'username' => 'exam.omar',
                'email' => 'examination@university.edu',
                'password' => 'Exam@123456',
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'Examination',
            ],
            [
                'username' => 'affairs.khaled',
                'email' => 'studentaffairs@university.edu',
                'password' => 'Affairs@123456',
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'StudentAffairs',
            ],
            [
                'username' => 'dr.ahmad',
                'email' => 'instructor@university.edu',
                'password' => 'Instructor@123456',
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'Instructor',
            ],
            [
                'username' => 'ta.sarah',
                'email' => 'ta@university.edu',
                'password' => 'Ta@123456',
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'TeachingAssistant',
            ],
            [
                'username' => 'student.omar',
                'email' => 'student1@university.edu',
                'password' => 'Student@123456',
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'Student',
            ],
            [
                'username' => 'student.layla',
                'email' => 'student2@university.edu',
                'password' => 'Student@123456',
                'status' => 'active',
                'email_verified_at' => now(),
                'role' => 'Student',
            ],
            [
                'person_id' => 1,
                'username' => 'ahmad.ali',
                'email' => 'ahmad.ali@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-20',
                'created_at' => '2022-09-01',
                'role' => 'Student',
            ],
            [
                'person_id' => 2,
                'username' => 'fatima.hussein',
                'email' => 'fatima.hussein@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-21',
                'created_at' => '2022-09-01',
                'role' => 'Student',
            ],
            [
                'person_id' => 3,
                'username' => 'youssef.hamwi',
                'email' => 'youssef.hamwi@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-19',
                'created_at' => '2022-09-01',
                'role' => 'Student',
            ],
            [
                'person_id' => 4,
                'username' => 'noura.hussein',
                'email' => null,
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => null,
                'last_login' => '2025-05-18',
                'created_at' => '2022-09-01',
                'role' => 'Student',
            ],
            [
                'person_id' => 5,
                'username' => 'mohammed.nour',
                'email' => 'm.nour@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-22',
                'created_at' => '2015-02-10',
                'role' => 'Instructor',
            ],
            [
                'person_id' => 6,
                'username' => 'salma.abdelrahman',
                'email' => 's.abdelrahman@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-20',
                'created_at' => '2016-09-15',
                'role' => 'Instructor',
            ],
            [
                'person_id' => 7,
                'username' => 'khaled.sayed',
                'email' => 'khaled.sayed@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-18',
                'created_at' => '2010-10-01',
                'role' => 'Instructor',
            ],
            [
                'person_id' => 8,
                'username' => 'rana.akkad',
                'email' => null,
                'password' => 'password123',
                'status' => 'inactive',
                'email_verified_at' => null,
                'last_login' => '2024-12-01',
                'created_at' => '2018-03-01',
                'role' => 'TeachingAssistant',
            ],
            [
                'person_id' => 9,
                'username' => 'ammar.khatib',
                'email' => 'ammar.khatib@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-21',
                'created_at' => '2017-01-10',
                'role' => 'TeachingAssistant',
            ],
            [
                'person_id' => 10,
                'username' => 'lina.azzam',
                'email' => 'lina.azzam@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-17',
                'created_at' => '2022-09-01',
                'role' => 'Student',
            ],
            [
                'person_id' => 11,
                'username' => 'rami.khatib',
                'email' => null,
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => null,
                'last_login' => '2025-05-16',
                'created_at' => '2022-09-01',
                'role' => 'Student',
            ],
            [
                'person_id' => 12,
                'username' => 'hiba.mustafa',
                'email' => 'hiba.mustafa@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-15',
                'created_at' => '2019-09-01',
                'role' => 'AdministrativeStaff',
            ],
            [
                'person_id' => 13,
                'username' => 'samer.abed',
                'email' => 'samer.abed@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-14',
                'created_at' => '2014-09-01',
                'role' => 'SystemAdministrator',
            ],
            [
                'person_id' => 14,
                'username' => 'duaa.sheikh',
                'email' => null,
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => null,
                'last_login' => '2025-05-13',
                'created_at' => '2020-09-01',
                'role' => 'Student',
            ],
            [
                'person_id' => 15,
                'username' => 'hussam.halabi',
                'email' => 'hussam.halabi@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'inactive',
                'email_verified_at' => now(),
                'last_login' => '2023-06-01',
                'created_at' => '2011-09-01',
                'role' => 'Instructor',
            ],
            [
                'person_id' => 16,
                'username' => 'maisa.hammoud',
                'email' => 'maisa.hammoud@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-12',
                'created_at' => '2022-09-01',
                'role' => 'Student',
            ],
            [
                'person_id' => 17,
                'username' => 'basel.nouri',
                'email' => null,
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => null,
                'last_login' => '2025-05-11',
                'created_at' => '2023-09-01',
                'role' => 'Student',
            ],
            [
                'person_id' => 18,
                'username' => 'reem.khoury',
                'email' => 'reem.khoury@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-10',
                'created_at' => '2016-09-01',
                'role' => 'TeachingAssistant',
            ],
            [
                'person_id' => 19,
                'username' => 'ghassan.hafez',
                'email' => 'ghassan.hafez@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-09',
                'created_at' => '2005-09-01',
                'role' => 'Instructor',
            ],
            [
                'person_id' => 20,
                'username' => 'nancy.saleh',
                'email' => 'nancy.saleh@damascusuniv.edu.sy',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
                'last_login' => '2025-05-08',
                'created_at' => '2019-09-01',
                'role' => 'Dean',
            ],

        ];


        foreach ($users as $userData) {
            DB::transaction(function () use ($userData) {
                $roleName = $userData['role'];
                unset($userData['role']);

                $user = $this->userRepo->create($userData);
                $this->userRepo->assignRole($user, $roleName);
            });
        }
    }
}
