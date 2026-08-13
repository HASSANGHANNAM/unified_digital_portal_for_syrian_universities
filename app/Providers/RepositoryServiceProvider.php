<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\UniversityRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\PersonRepositoryInterface;
use App\Repositories\Contracts\CollegeRepositoryInterface;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Repositories\Contracts\StaffRepositoryInterface;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Contracts\TeachingAssistantRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\AccountRoleRepositoryInterface;
use App\Repositories\Contracts\UniversalCourseRepositoryInterface;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\Repositories\Contracts\CoursePartRepositoryInterface;
use App\Repositories\Contracts\LectureRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\Contracts\StudentCourseRepositoryInterface;
use App\Repositories\Contracts\StudentCoursePartRepositoryInterface;
use App\Repositories\Contracts\CourseStaffRepositoryInterface;
use App\Repositories\Contracts\SanctionTypeRepositoryInterface;
use App\Repositories\Contracts\SanctionRepositoryInterface;
use App\Repositories\Contracts\RequestTypeRepositoryInterface;
use App\Repositories\Contracts\StudentRequestRepositoryInterface;
use App\Repositories\Contracts\RequestTypeMediaRepositoryInterface;
use App\Repositories\Contracts\RequestMediaRepositoryInterface;
use App\Repositories\Contracts\SuggestionRepositoryInterface;
use App\Repositories\Contracts\PersonAttachmentRepositoryInterface;
use App\Repositories\Contracts\DepartmentHeadRepositoryInterface;
use App\Repositories\Contracts\CollegeDeanRepositoryInterface;
use App\Repositories\Contracts\RequestTypeAvailabilityRepositoryInterface;
use App\Repositories\Contracts\EmailVerificationRepositoryInterface;
use App\Repositories\Contracts\StudyPlanCourseRepositoryInterface;
use App\Repositories\Contracts\StudentVerificationRepositoryInterface;
// use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Repositories\Contracts\AdvertisementRepositoryInterface;

use App\Repositories\UniversityRepository;
use App\Repositories\UserRepository;
use App\Repositories\PersonRepository;
use App\Repositories\CollegeRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\StaffRepository;
use App\Repositories\DoctorRepository;
use App\Repositories\TeachingAssistantRepository;
use App\Repositories\StudentRepository;
use App\Repositories\RoleRepository;
use App\Repositories\AccountRoleRepository;
use App\Repositories\UniversalCourseRepository;
use App\Repositories\CourseRepository;
use App\Repositories\CoursePartRepository;
use App\Repositories\LectureRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\StudentCourseRepository;
use App\Repositories\StudentCoursePartRepository;
use App\Repositories\CourseStaffRepository;
use App\Repositories\SanctionTypeRepository;
use App\Repositories\SanctionRepository;
use App\Repositories\RequestTypeRepository;
use App\Repositories\StudentRequestRepository;
use App\Repositories\RequestTypeMediaRepository;
use App\Repositories\RequestMediaRepository;
use App\Repositories\SuggestionRepository;
use App\Repositories\PersonAttachmentRepository;
use App\Repositories\DepartmentHeadRepository;
use App\Repositories\CollegeDeanRepository;
use App\Repositories\Contracts\RequestUserRepositoryInterface;
use App\Repositories\Contracts\UserSignatureRepositoryInterface;
use App\Repositories\RequestTypeAvailabilityRepository;
use App\Repositories\EmailVerificationRepository;
use App\Repositories\RequestUserRepository;
use App\Repositories\StudyPlanCourseRepository;
use App\Repositories\StudentVerificationRepository;
use App\Repositories\UserSignatureRepository;
use App\Repositories\AdvertisementRepository;
use App\Repositories\CollegeFileRepository;
use App\Repositories\Contracts\CollegeFileRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UniversityRepositoryInterface::class, UniversityRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PersonRepositoryInterface::class, PersonRepository::class);
        $this->app->bind(CollegeRepositoryInterface::class, CollegeRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(DoctorRepositoryInterface::class, DoctorRepository::class);
        $this->app->bind(TeachingAssistantRepositoryInterface::class, TeachingAssistantRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(AccountRoleRepositoryInterface::class, AccountRoleRepository::class);
        $this->app->bind(UniversalCourseRepositoryInterface::class, UniversalCourseRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(CoursePartRepositoryInterface::class, CoursePartRepository::class);
        $this->app->bind(LectureRepositoryInterface::class, LectureRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(StudentCourseRepositoryInterface::class, StudentCourseRepository::class);
        $this->app->bind(StudentCoursePartRepositoryInterface::class, StudentCoursePartRepository::class);
        $this->app->bind(CourseStaffRepositoryInterface::class, CourseStaffRepository::class);
        $this->app->bind(SanctionTypeRepositoryInterface::class, SanctionTypeRepository::class);
        $this->app->bind(SanctionRepositoryInterface::class, SanctionRepository::class);
        $this->app->bind(RequestTypeRepositoryInterface::class, RequestTypeRepository::class);
        $this->app->bind(StudentRequestRepositoryInterface::class, StudentRequestRepository::class);
        $this->app->bind(RequestTypeMediaRepositoryInterface::class, RequestTypeMediaRepository::class);
        $this->app->bind(RequestMediaRepositoryInterface::class, RequestMediaRepository::class);
        $this->app->bind(SuggestionRepositoryInterface::class, SuggestionRepository::class);
        $this->app->bind(PersonAttachmentRepositoryInterface::class, PersonAttachmentRepository::class);
        $this->app->bind(DepartmentHeadRepositoryInterface::class, DepartmentHeadRepository::class);
        $this->app->bind(CollegeDeanRepositoryInterface::class, CollegeDeanRepository::class);
        $this->app->bind(RequestTypeAvailabilityRepositoryInterface::class, RequestTypeAvailabilityRepository::class);
        $this->app->bind(EmailVerificationRepositoryInterface::class, EmailVerificationRepository::class);
        $this->app->bind(StudyPlanCourseRepositoryInterface::class, StudyPlanCourseRepository::class);
        $this->app->bind(StudentVerificationRepositoryInterface::class, StudentVerificationRepository::class);
        // $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(UserSignatureRepositoryInterface::class, UserSignatureRepository::class);
        $this->app->bind(RequestUserRepositoryInterface::class, RequestUserRepository::class);
        $this->app->bind(AdvertisementRepositoryInterface::class, AdvertisementRepository::class);
        $this->app->bind(CollegeFileRepositoryInterface::class, CollegeFileRepository::class);
    }
    public function boot(): void
    {
        //
    }
}
