<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\RequestTypeRepositoryInterface;
use App\DTOs\RequestTypeDTO;
use App\Services\Traits\TokenDataTrait;

class RequestService
{
    use TokenDataTrait;
    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private RequestTypeRepositoryInterface $requestTypeRepository
    ) {}

    public function getStudentRequests(array $data): array
    {
        $message = 'قائمة الطلبات التي قدمها الطالب.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function createRequest(array $data): array
    {
        $message = 'تقديم طلب طلابي جديد.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getRequestDetails(array $data, int $requestId): array
    {
        $message = 'عرض تفاصيل طلب معين وحالته.';
        $code = 200;
        $data = array_merge($data, ["requestId" => $requestId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function cancelRequest(array $data, int $requestId): array
    {
        $message = 'إلغاء طلب (إذا لم تتم معالجته).';
        $code = 200;
        $data = array_merge($data, ["requestId" => $requestId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getAllRequests(array $data): array
    {
        $message = 'قائمة الطلبات الواردة للإداري (شؤون طلاب / امتحانات) مع تصفية حسب النوع.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function reviewRequest(array $data, int $requestId): array
    {
        $message = 'مراجعة الطلب وقبوله أو رفضه مع إضافة سبب.';
        $code = 200;
        $data = array_merge($data, ["requestId" => $requestId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }
    public function requestsInStudentCollege(array $request): array
    {
        $message = 'قائمة الطلبات التي يمكن أن يقدمها الطالب في الكلية.';
        $code = 200;
        $data = [];
        $collegeId = $this->getStudentCollegeId();
        $types = $this->requestTypeRepository->getByCollegeId($collegeId, $request);

        $data = RequestTypeDTO::fromServiceData($types);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }
}
