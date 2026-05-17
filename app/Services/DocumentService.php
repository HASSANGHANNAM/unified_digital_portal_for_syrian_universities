<?php

namespace App\Services;

use App\DTOs\StudentDocumentDTO;
use App\Repositories\Contracts\UserRepositoryInterface;

class DocumentService
{
      public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
    ) {}

    public function addDocument(array $data, int $studentId): array
    {
        $message = 'إضافة مستند إلى ملف الطالب (لشؤون الطلاب).';
        $code = 200;
        $data = array_merge($data, ["studentId" => $studentId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getDocuments(array $data, int $studentId): array
    {
        $message = 'عرض مستندات ملف الطالب.';
        $code = 200;
        // use App\DTOs\StudentDocumentDTO;
        $data = array_merge($data, ["studentId" => $studentId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

}
