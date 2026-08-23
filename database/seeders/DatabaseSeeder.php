<?php

namespace Database\Seeders;

use Database\Seeders\Academic\CollegeDeanSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\Academic\UniversitySeeder;
use Database\Seeders\Core\PersonSeeder;
use Database\Seeders\Academic\CollegeSeeder;
use Database\Seeders\Academic\DepartmentHeadSeeder;
use Database\Seeders\Academic\DepartmentSeeder;
use Database\Seeders\Academic\GroupSeeder;
use Database\Seeders\Academic\DoctorSeeder;
use Database\Seeders\Academic\ScheduleGroupSeeder;
use Database\Seeders\Academic\ScheduleSeeder;
use Database\Seeders\Academic\StaffSeeder;
use Database\Seeders\Academic\StudentGroupSeeder;
use Database\Seeders\Academic\TeachingAssistantSeeder;
use Database\Seeders\Core\PersonAttachmentSeeder;
use Database\Seeders\Core\UsersSeeder;
use Database\Seeders\Students\StudentSeeder;
use Database\Seeders\Requests\RequestTypeSeeder;
use Database\Seeders\Sanctions\SanctionTypeSeeder;
use Database\Seeders\Courses\UniversalCourseSeeder;
use Database\Seeders\Courses\CourseSeeder;
use Database\Seeders\Courses\CoursePartSeeder;
use Database\Seeders\Courses\CourseStaffSeeder;
use Database\Seeders\Courses\LectureSeeder;
use Database\Seeders\Courses\StudentCourseSeeder;
use Database\Seeders\Requests\RequestSeeder;
use Database\Seeders\Sanctions\SanctionSeeder;
use Database\Seeders\Students\SuggestionSeeder;
use Database\Seeders\Courses\StudentCoursePartSeeder;
use Database\Seeders\Requests\RequestMediaSeeder;
use Database\Seeders\Requests\RequestTypeMediaSeeder;
use App\Models\University;
use App\Models\Person;
use Database\Seeders\Courses\StudyPlanCourseSeeder;
use Database\Seeders\Requests\RequestTypeAvailabilitySeeder;
use Database\Seeders\SeedersV2\CollegeSeederV2;
use Database\Seeders\SeedersV2\DepartmentSeederV2;
use Database\Seeders\SeedersV2\RolesAndPermissionsSeeders\RolesAndPermissionsSeederV2;
use Database\Seeders\SeedersV2\StudentSeederV2;
use Database\Seeders\SeedersV2\UniversitySeederV2;
use Database\Seeders\SeedersV2\UsersSeeders\PersonAttachmentsSeederV2;
use Database\Seeders\SeedersV2\UsersSeeders\PersonSeederV2;
use Database\Seeders\SeedersV2\UsersSeeders\UsersSeederV2;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // تشغيل جميع السيدرات أولاً
        $this->call([
            // RolesAndPermissionsSeeder::class,
            RolesAndPermissionsSeederV2::class,
            // UniversitySeeder::class,
            UniversitySeederV2::class,
            // PersonSeeder::class,
            PersonSeederV2::class,
            // CollegeSeeder::class,
            CollegeSeederV2::class,
            // DepartmentSeeder::class,
            DepartmentSeederV2::class,
            StaffSeeder::class,
            // UsersSeeder::class,
            UsersSeederV2::class,
            DoctorSeeder::class,
            TeachingAssistantSeeder::class,
            DepartmentHeadSeeder::class,
            CollegeDeanSeeder::class,
            // StudentSeeder::class,
            StudentSeederV2::class,
            RequestTypeSeeder::class,
            SanctionTypeSeeder::class,
            UniversalCourseSeeder::class,
            CourseSeeder::class,
            StudyPlanCourseSeeder::class,
            CoursePartSeeder::class,
            StudentCourseSeeder::class,
            // RequestSeeder::class,
            SanctionSeeder::class,
            SuggestionSeeder::class,
            StudentCoursePartSeeder::class,
            LectureSeeder::class,
            // ScheduleSeeder::class,
            // GroupSeeder::class,
            // StudentGroupSeeder::class,
            // ScheduleGroupSeeder::class,
            CourseStaffSeeder::class,
            RequestTypeMediaSeeder::class,
            RequestMediaSeeder::class,
            // PersonAttachmentSeeder::class,
            PersonAttachmentsSeederV2::class,
            RequestTypeAvailabilitySeeder::class,
        ]);
    }
}
