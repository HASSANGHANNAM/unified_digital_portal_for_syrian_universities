<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run() {
        $deanRole = Role::create(['name' => 'Dean']);
        $headofdepartmentRole = Role::create(['name' => 'HeadOfDepartment']);
        $examinationRole = Role::create(['name' => 'Examination']);
        $studentaffairsRole = Role::create(['name'=>'StudentAffairs']);
        $instructorRole = Role::create(['name'=>'Instructor']);
        $teachingassistantRole = Role::create(['name'=>'TeachingAssistant']);
        $studentRole = Role::create(['name'=>'Student']);


        $permissions = [
            'create_course',
            'edit_course',
            'delete_course',
            'view_course',

            'add_grade',
            'edit_grade',
            'view_grade',
            'delete_grade',

            'enroll_student',
            'remove_student',
            'view_students',
            'manage_student_affairs',

            'create_exam',
            'edit_exam',
            'view_exam_results',
            'approve_exam',
            
            'view_dashboard',
            'manage_users',
            'view_reports',
        ];

        foreach ($permissions as $permissionsname) { // اذا موجودة تركها
            Permission::findOrCreate($permissionsname,/* guardName:*/ 'web');
        }

        //assign permissions to role
        $deanRole->givePermissionTo([]);
        $headofdepartmentRole->givePermissionTo([]);
        $examinationRole->givePermissionTo([]);
        $studentaffairsRole->givePermissionTo([]);
        $instructorRole->givePermissionTo([]);
        $teachingassistantRole->givePermissionTo([]);
        $studentRole->givePermissionTo([]);

    }
}
