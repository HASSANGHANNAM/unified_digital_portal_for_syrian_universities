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
                'person_id' => 1,
                'email_verified_at' => now(),
                'role' => 'Dean',
            ],
            [
                'username' => 'hod.mohammad',
                'email' => 'hod@university.edu',
                'password' => 'Hod@123456',
                'status' => 'active',
                'person_id' => 2,
                'email_verified_at' => now(),
                'role' => 'HeadOfDepartment',
            ],
            [
                'username' => 'exam.omar',
                'email' => 'examination@university.edu',
                'password' => 'Exam@123456',
                'status' => 'active',
                'person_id' => 3,
                'email_verified_at' => now(),
                'role' => 'Examination',
            ],
            [
                'username' => 'affairs.khaled',
                'email' => 'studentaffairs@university.edu',
                'password' => 'Affairs@123456',
                'status' => 'active',
                'person_id' => 4,
                'email_verified_at' => now(),
                'role' => 'StudentAffairs',
            ],
            [
                'username' => 'dr.ahmad',
                'email' => 'instructor@university.edu',
                'password' => 'Instructor@123456',
                'status' => 'active',
                'person_id' => 5,
                'email_verified_at' => now(),
                'role' => 'Instructor',
            ],
            [
                'username' => 'ta.sarah',
                'email' => 'ta@university.edu',
                'password' => 'Ta@123456',
                'status' => 'active',
                'person_id' => 6,
                'email_verified_at' => now(),
                'role' => 'TeachingAssistant',
            ],
            [
                'username' => 'student.omar',
                'email' => 'student1@university.edu',
                'password' => 'Student@123456',
                'status' => 'active',
                'person_id' => 7,
                'email_verified_at' => now(),
                'role' => 'Student',
            ],
            [
                'username' => 'student.layla',
                'email' => 'student2@university.edu',
                'password' => 'Student@123456',
                'status' => 'active',
                'person_id' => 8,
                'email_verified_at' => now(),
                'role' => 'Student',
            ],
            [
                'username' => 'student.ali',
                'email' => 'student3@university.edu',
                'password' => 'Student@123456',
                'status' => 'active',
                'person_id' => 9,
                'email_verified_at' => now(),
                'role' => 'Student',
            ],
            [
                'username' => 'student.noor',
                'email' => 'student4@university.edu',
                'password' => 'Student@123456',
                'status' => 'active',
                'person_id' => 10,
                'email_verified_at' => now(),
                'role' => 'Student',
            ],
            [
                'username' => 'student.yousef',
                'email' => 'student5@university.edu',
                'password' => 'Student@123456',
                'status' => 'inactive',
                'person_id' => 11,
                'email_verified_at' => now(),
                'role' => 'Student',
            ],
            [
                'username' => 'student.maya',
                'email' => 'student6@university.edu',
                'password' => 'Student@123456',
                'status' => 'active',
                'person_id' => 12,
                'email_verified_at' => now(),
                'role' => 'Student',
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
