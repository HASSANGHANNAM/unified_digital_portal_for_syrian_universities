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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // تشغيل جميع السيدرات أولاً
        $this->call([
            RolesAndPermissionsSeeder::class,
            UniversitySeeder::class,
            PersonSeeder::class,
            CollegeSeeder::class,
            DepartmentSeeder::class,
            StaffSeeder::class,
            UsersSeeder::class,
            DoctorSeeder::class,
            TeachingAssistantSeeder::class,
            DepartmentHeadSeeder::class,
            CollegeDeanSeeder::class,
            StudentSeeder::class,
            RequestTypeSeeder::class,
            SanctionTypeSeeder::class,
            UniversalCourseSeeder::class,
            CourseSeeder::class,
            CoursePartSeeder::class,
            StudentCourseSeeder::class,
            RequestSeeder::class,
            SanctionSeeder::class,
            SuggestionSeeder::class,
            StudentCoursePartSeeder::class,
            LectureSeeder::class,
            ScheduleSeeder::class,
            GroupSeeder::class,
            StudentGroupSeeder::class,
            ScheduleGroupSeeder::class,
            CourseStaffSeeder::class,
            RequestTypeMediaSeeder::class,
            RequestMediaSeeder::class,
            PersonAttachmentSeeder::class,
        ]);

        // بعد الانتهاء من جميع السيدرات، نقوم بتعيين مدير جامعة دمشق
        $damascus = University::where('name', 'جامعة دمشق')->first();
        $director = Person::where('national_id', '01012345690')->first();
        if ($damascus && $director) {
            $damascus->update(['university_director_id' => $director->id]);
        }
    }
}
