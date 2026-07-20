<?php

namespace App\Services;

use App\DTOs\ApproveRequestDTO;
use App\DTOs\AssignRequestDTO;
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
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
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

    public function getStaffRequests($request): array
    {

        $userId = auth()->id();
        abort_if(!$userId, 403, 'يجب تسجيل الدخول أولاً.');

        $name = null;
        $collegeId = $request['college_id'];
        $perPage = 15;
        if (empty($collegeId)) {
            abort(422, 'معرف الكلية مطلوب.');
        }
        if ($request instanceof Request) {
            $name = $request->input('name');
            $collegeId = $request->input('college_id');
            $perPage = $request->input('per_page', 15);
        } elseif (is_array($request)) {
            $name = $request['name'] ?? null;
            $perPage = $request['per_page'] ?? 15;
        }

        $perPage = (int) $perPage;
        $perPage = $perPage > 0 ? $perPage : 15;

        $requests = $this->requestRepository->getStaffRequestsWithFilters(
            $userId,
            [
                'name' => $name,
                'college_id' => $collegeId,
            ],
            $perPage
        );

        return [
            'data' => RequestListDTO::fromPaginator($requests),
            'message' => 'قائمة الطلبات المكلف بتوقيعها.',
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
    public function getStaffRequestDetails(int $requestId): array
    {
        $request = $this->requestRepository->staffFindWithDetailsAndMedia($requestId);
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

    public function getAvailableRequestTypes(): array
    {
        $user = Auth::user();

        $student = Student::where('person_id', $user->person_id)->first();

        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $requestTypes = $this->requestTypeRepository
            ->getAvailableRequestTypesByCollege($student->college_id);



        $data = $requestTypes->map(function ($type) {
            return [
                'request_type_id' => $type->id,
                'name' => $type->name,
                'description' => $type->description,
                'requestTypeMedia' => $type->requestTypeMedia->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'request_type_id' => $media->request_type_id,
                        'name' => $media->name,
                        'type' => $media->type,
                    ];
                })->values(),
            ];
        });

        return [
            'data' => $data,
            'message' => 'Request types retrieved successfully.',
            'code' => 200,
        ];
    }

    public function getRequestsList(): array
    {
        $user = Auth::user();

        $student = Student::where('person_id', $user->person_id)->first();

        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $perPage = request()->input('per_page', 10);

        $requests = $this->requestRepository
            ->getStudentRequests($student->id, $perPage);

        $data = collect($requests->items())->map(function ($request) {

            return [
                'request_id' => $request->id,
                'request_type_id' => $request->request_type_id,
                'reason' => $request->reason,
                'submission_date' => $request->submission_date,
                'decision_date' => $request->decision_date,
                'decision_reason' => $request->decision_reason,

                'course' => $request->course
                    ? [
                        'name' => $request->course->universalCourse->name ?? '',
                        'code' => $request->course->code ?? '',
                    ]
                    : null,

                'status' => $request->status,

                'staff' => $request->processedBy
                    ? [
                        'name' => $request->processedBy->person->full_name ?? '',
                    ]
                    : null,
            ];
        });

        return [
            'data' => [
                'requests' => $data,
                'meta' => [
                    'current_page' => $requests->currentPage(),
                    'last_page'    => $requests->lastPage(),
                    'per_page'     => $requests->perPage(),
                    'total'        => $requests->total(),
                ]
            ],
            'message' => 'Requests retrieved successfully.',
            'code' => 200,
        ];
    }

    public function RequestDetails(int $requestId): array
    {
        $user = Auth::user();
        $student = Student::where('person_id', $user->person_id)->first();

        if (!$student) {
            return [
                'data' => [],
                'message' => 'Student not found.',
                'code' => 404,
            ];
        }

        $request = $this->requestRepository
            ->findRequestDetails($requestId, $student->id);

        if (!$request) {
            return [
                'data' => [],
                'message' => 'Request not found.',
                'code' => 404,
            ];
        }

        $data = [
            'request_id' => $request->id,
            'reason' => $request->reason,
            'submission_date' => $request->submission_date,
            'decision_date' => $request->decision_date,
            'decision_reason' => $request->decision_reason,

            'course' => $request->course
                ? [
                    'name' => $request->course->universalCourse->name ?? '',
                    'code' => $request->course->code ?? '',
                ]
                : null,

            'status' => $request->status,

            'staff' => $request->processedBy
                ? [
                    'name' => $request->processedBy->person->full_name ?? '',
                ]
                : null,

            'requestType' => [
                'request_type_id' => $request->requestType->id,
                'name' => $request->requestType->name,
                'description' => $request->requestType->description,

                'requestTypeMedia' => $request->requestType->requestTypeMedia
                    ->map(function ($media) {
                        return [
                            'id' => $media->id,
                            'name' => $media->name,
                            'type' => $media->type,
                        ];
                    })
                    ->values(),
            ],

            'media' => $request->media
                ->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'name' => $media->name,
                        'type' => $media->type,
                    ];
                })
                ->values(),
        ];

        return [
            'data' => $data,
            'message' => 'Request details retrieved successfully.',
            'code' => 200,
        ];
    }
    public function assignRequest(array $data): array
    {
        $userId = auth()->id();
        abort_if(!$userId, 403, 'يجب تسجيل الدخول أولاً.');
        $requestId = $data['request_id'] ?? null;
        $collegeId = $data['college_id'] ?? null;
        if (!$requestId || !$collegeId) {
            abort(422, 'بيانات غير مكتملة.');
        }
        $result = $this->requestRepository->assignRequestToUser(
            (int) $requestId,
            $userId,
            (int) $collegeId
        );
        $dto = new AssignRequestDTO(
            $result['request_id'],
            $result['status'],
            'تم استلام الطلب بنجاح.'
        );
        return [
            'data' => $dto->toArray(),
            'message' => 'تم استلام الطلب بنجاح.',
            'code' => 200,
        ];
    }
    public function approveRequest(array $data): array
    {
        $userId = auth()->id();
        abort_if(!$userId, 403, 'يجب تسجيل الدخول أولاً.');
        $requestUserId = $data['request_user_id'] ?? null;
        $decision = $data['decision'] ?? null;
        if (!$requestUserId || !$decision) {
            abort(422, 'بيانات غير مكتملة.');
        }
        $result = $this->requestRepository->approveRequest(
            (int) $requestUserId,
            $userId,
            $decision
        );
        $dto = new ApproveRequestDTO(
            $result['request_id'],
            $result['status'],
            $result['decision'],
            $decision === 'approved' ? 'تمت الموافقة على الطلب.' : 'تم رفض الطلب.'
        );
        return [
            'data' => $dto->toArray(),
            'message' => $dto->message,
            'code' => 200,
        ];
    }
}
