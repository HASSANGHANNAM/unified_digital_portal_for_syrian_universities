<?php

namespace App\Services;

use App\DTOs\AllGradesDTO;
use App\Repositories\Contracts\UserRepositoryInterface;

class GradeService
{
     public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
    ) {}

    public function getAllGrades(array $data): array
    {
        $message = 'عرض علامات جميع الطلاب (لشؤون الامتحانات).';
        $code = 200;
        // use App\DTOs\AllGradesDTO;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function addGrade(array $data): array
    {
        $message = 'إدخال أو تعديل علامة طالب.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getGradeAppeals(array $data): array
    {
        $message = 'قائمة الاعتراضات على العلامات.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function processAppeal(array $data, int $appealId): array
    {
        $message = 'معالجة اعتراض (تعديل العلامة أو رفض الاعتراض).';
        $code = 200;
        $data = array_merge($data, ["appealId" => $appealId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

}
