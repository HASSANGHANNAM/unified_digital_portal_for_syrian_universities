<?php

namespace App\Services;

use App\DTOs\ScheduleDTO;
use App\DTOs\GradesDTO;
use App\Repositories\Contracts\UserRepositoryInterface;

class AcademicService
{
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
    ) {}

    public function getSchedule(array $data): array
    {
        $message = 'عرض الجدول الدراسي للطالب.';
        $code = 200;
        // use App\DTOs\ScheduleDTO;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getStudentCourses(array $data): array
    {
        $message = 'عرض المقررات المسجلة للطالب.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getGrades(array $data): array
    {
        $message = 'عرض العلامات والنتائج الأكاديمية.';
        $code = 200;
        // use App\DTOs\GradesDTO;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getInstructorCourses(array $data): array
    {
        $message = 'عرض قائمة المقررات التي يدرسها الدكتور/المعيد.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getTeachingAssistants(array $data): array
    {
        $message = 'عرض المعيدين التابعين لمادة معينة.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }
}
