<?php

namespace App\Services;

use App\DTOs\AllGradesDTO;
use App\Imports\StudentMarksImport;
use App\Models\Course;
use App\Repositories\Contracts\UserRepositoryInterface;
use Maatwebsite\Excel\Facades\Excel;

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
        $course = Course::find($data['course_id']);
        if (!$course) {
            return [
                'data' => [],
                'message' => 'Course not found.',
                'code' => 404,
            ];
        }
        $import = new StudentMarksImport($course->id);

        try {
            Excel::import($import, $data['file']);
        } catch (\Maatwebsite\Excel\Exceptions\ValidationException $exception) {
            return [
                'data' => [
                    'errors' => $exception->errors(),
                ],
                'message' => 'Excel validation failed.',
                'code' => 422,
            ];
        } catch (\Throwable $exception) {
            return [
                'data' => [
                    'exception' => $exception->getMessage(),
                ],
                'message' => 'Failed importing grades.',
                'code' => 500,
            ];
        }

        $report = $import->getReport();
        return [
            'data' => $report,
            'message' => 'تم رفع العلامات بنجاح.',
            'code' => 200,
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
