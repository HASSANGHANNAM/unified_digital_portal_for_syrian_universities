<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\RequestTypeRepositoryInterface;
use App\DTOs\RequestTypeDTO;
use App\DTOs\RequestTypeMediaDTO;
use App\DTOs\RequestListDTO;
use App\DTOs\RequestDetailsDTO;
use App\Repositories\Contracts\RequestTypeMediaRepositoryInterface;
use App\Services\Traits\TokenDataTrait;
use App\Repositories\RequestRepository;
use App\Repositories\RequestMediaRepository;
use App\DTOs\RequestResponseDTO;
use App\Services\Traits\RequestMediaValidationTrait;
use Illuminate\Http\Request;
use App\Models\Request as RequestModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class RequestService
{
    use TokenDataTrait;
    use RequestMediaValidationTrait;

    public function __construct(
        private UserRepositoryInterface $userRepositoryInterface,
        private RequestTypeRepositoryInterface $requestTypeRepository,
        private RequestTypeMediaRepositoryInterface $requestTypeMediaRepository,
        private RequestRepository $requestRepository,
        private RequestMediaRepository $requestMediaRepository
    ) {}

    public function getStudentRequests($request): array
    {
        $studentId = $this->getStudentId();
        abort_if(!$studentId, 403);
        $status = null;
        $name = null;
        $perPage = 15;

        if ($request instanceof Request) {
            $status = $request->input('status');
            $name = $request->input('name');
            $perPage = $request->input('per_page', 15);
        } elseif (is_array($request)) {
            $status = $request['status'] ?? null;
            $name = $request['name'] ?? null;
            $perPage = $request['per_page'] ?? 15;
        }

        $perPage = (int) $perPage;
        $perPage = $perPage > 0 ? $perPage : 15;
        $requests = $this->requestRepository->getStudentRequestsWithFilters(
            $studentId,
            ['status' => $status, 'name' => $name],
            $perPage
        );

        return [
            'data' => RequestListDTO::fromPaginator($requests),
            'message' => 'قائمة الطلبات التي قدمها الطالب.',
            'code' => 200,
        ];
    }

    public function getRequestDetails(int $requestId): array
    {
        $studentId = $this->getStudentId();
        abort_if(!$studentId, 403);
        $request = $this->requestRepository->findWithDetailsAndMedia($requestId, $studentId);
        abort_if(!$request, 403);
        return [
            'data' => RequestDetailsDTO::fromModel($request)->toArray(),
            'message' => 'تفاصيل الطلب المحدد.',
            'code' => 200,
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

    public function cancelRequest(int $requestId): array
    {
        try {
            $request = RequestModel::where('id', $requestId)->first();
            if (!$request) {
                return ['data' => [], 'message' => 'Request not found', 'code' => 404];
            }
            \Illuminate\Support\Facades\Gate::authorize('cancel', $request);
            if ($request->status !== RequestModel::STATUS_PENDING) {
                return ['data' => [], 'message' => 'Only pending requests can be cancelled', 'code' => 400];
            }
            $updated = $this->requestRepository->updateStatus($requestId, RequestModel::STATUS_CANCELLED);
            if (!$updated) {
                return ['data' => [], 'message' => 'Unable to cancel request', 'code' => 400];
            }
            return ['data' => ['id' => (string) $updated->id, 'status' => $updated->status], 'message' => 'Request cancelled', 'code' => 200];
        } catch (Throwable $th) {
            return ['data' => [], 'message' => $th->getMessage(), 'code' => 400];
        }
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
    public function getMediaByRequestTypeId(int $requestTypeId): array
    {
        $media = $this->requestTypeMediaRepository->getByRequestTypeId($requestTypeId);

        return [
            'data' => RequestTypeMediaDTO::fromServiceData($media),
            'message' => 'قائمة وسائط نوع الطلب المحدد.',
            'code' => 200,
        ];
    }

    public function store(array $data, array $files): array
    {
        $studentId = $this->getStudentId();
        $requestType = $this->requestTypeRepository->getByIdWithMedia($data['request_type_id']);
        $this->validateRequestTypeMedia($requestType, $files);

        $requestModel = DB::transaction(function () use ($data, $files, $studentId) {
            $createData = [
                'student_id' => $studentId,
                'request_type_id' => $data['request_type_id'],
                'reason' => $data['reason'] ?? null,
                'course_id' => $data['course_id'] ?? null,
                'submission_date' => now(),
                'status' => 'pending',
            ];

            $request = $this->requestRepository->create($createData);

            foreach (array_values($files) as $file) {
                $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $path = "request_media/{$request->id}";
                Storage::disk('private')->putFileAs($path, $file, $filename);
                $this->requestMediaRepository->create([
                    'request_id' => $request->id,
                    'name' => $file->getClientOriginalName(),
                    'type' => $this->getFileType($file),
                    'path' => "{$path}/{$filename}",
                ]);
            }

            return $request->load('media');
        });

        return [
            'data' => RequestResponseDTO::fromModel($requestModel)->toArray(),
            'message' => 'تم تقديم الطلب بنجاح',
            'code' => 201,
        ];
    }
}
