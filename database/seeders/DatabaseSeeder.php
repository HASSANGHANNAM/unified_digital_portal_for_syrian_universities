<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// LEVEL 1
use Database\Seeders\Academic\UniversitySeeder;
use Database\Seeders\Core\PersonSeeder;

// LEVEL 2
use Database\Seeders\Academic\CollegeSeeder;

// LEVEL 3
use Database\Seeders\Academic\DepartmentSeeder;
use Database\Seeders\Academic\StaffSeeder;
use Database\Seeders\Core\UsersSeeder;

// LEVEL 4
use Database\Seeders\Students\StudentSeeder;
use Database\Seeders\Requests\RequestTypeSeeder;
use Database\Seeders\Sanctions\SanctionTypeSeeder;
use Database\Seeders\Courses\UniversalCourseSeeder;

// LEVEL 5
use Database\Seeders\Courses\CourseSeeder;

// LEVEL 6
use Database\Seeders\Courses\CoursePartSeeder;
use Database\Seeders\Courses\StudentCourseSeeder;
use Database\Seeders\Requests\RequestSeeder;
use Database\Seeders\Sanctions\SanctionSeeder;
use Database\Seeders\Students\SuggestionSeeder;

use Database\Seeders\Courses\StudentCoursePartSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            // LEVEL 1
            UniversitySeeder::class,
            PersonSeeder::class,

            // LEVEL 2
            CollegeSeeder::class,

            // LEVEL 3
            DepartmentSeeder::class,
            StaffSeeder::class,
            UsersSeeder::class,

            // LEVEL 4
            StudentSeeder::class,
            RequestTypeSeeder::class,
            SanctionTypeSeeder::class,
            UniversalCourseSeeder::class,

            // LEVEL 5
            CourseSeeder::class,

            // LEVEL 6
            CoursePartSeeder::class,
            StudentCourseSeeder::class,
            RequestSeeder::class,
            SanctionSeeder::class,
            SuggestionSeeder::class,

            // LEVEL 7
            StudentCoursePartSeeder::class,
        ]);
    }
}
