<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\RequestService;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Http\Requests\V1\CreateRequestRequest;
use App\Http\Requests\V1\GetAllRequestsRequest;
use App\Http\Requests\V1\GetRequestDetailsRequest;
use App\Http\Requests\V1\GetStudentRequestsRequest;
use App\Http\Requests\V1\ReviewRequestRequest;
use App\Http\Requests\V1\RequestsInStudentCollegeRequest;
use App\Http\Requests\V1\StoreRequestRequest;
use App\Http\Responses\Response;
use Throwable;
use App\Models\Request as StudentRequestModel;

class RequestController extends Controller
{
    private RequestService $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
        $this->middleware('auth:sanctum');
    }

    public function getStudentRequests(GetStudentRequestsRequest $getStudentRequestsRequest): JsonResponse
    {
        try {
            $data = $this->requestService->getStudentRequests($getStudentRequestsRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function createRequest(CreateRequestRequest $createRequestRequest): JsonResponse
    {
        try {
            $data = $this->requestService->createRequest($createRequestRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getRequestDetails(GetRequestDetailsRequest $getRequestDetailsRequest): JsonResponse
    {
        try {
            $validated = $getRequestDetailsRequest->validated();
            $data = $this->requestService->getRequestDetails((int) $validated['requestId']);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function cancelRequest(int $requestId): JsonResponse
    {
        try {
            if (!is_numeric($requestId) || (int) $requestId < 1) {
                return Response::Error([], 'Invalid request id', 400);
            }
            $requestModel = StudentRequestModel::where('id', $requestId)->first();
            if (!$requestModel) {
                return Response::Error([], 'Request not found', 404);
            }
            $data = $this->requestService->cancelRequest($requestId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getAllRequests(GetAllRequestsRequest $getAllRequestsRequest): JsonResponse
    {
        try {
            $data = $this->requestService->getAllRequests($getAllRequestsRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function reviewRequest(ReviewRequestRequest $reviewRequestRequest, int $requestId): JsonResponse
    {
        try {
            $data = $this->requestService->reviewRequest($reviewRequestRequest->validated(), $requestId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function requestsInStudentCollege(RequestsInStudentCollegeRequest $requestsInStudentCollegeRequest): JsonResponse
    {
        try {
            $data = $this->requestService->requestsInStudentCollege($requestsInStudentCollegeRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function store(StoreRequestRequest $storeRequestRequest): JsonResponse
    {
        try {
            $files = $storeRequestRequest->file('media') ?? [];
            $data = $this->requestService->store($storeRequestRequest->validated(), $files);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function getMediaByRequestTypeId(int $requestTypeId): JsonResponse
    {
        try {
            $data = $this->requestService->getMediaByRequestTypeId($requestTypeId);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

        public function getRequestTypes(): JsonResponse
    {
        try {
            $data = $this->requestService->getAvailableRequestTypes();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function getRequestsList(): JsonResponse
    {
        try {
            $data = $this->requestService->getRequestsList();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function RequestDetails(int $requestId): JsonResponse
    {
        try {
            $data = $this->requestService->RequestDetails($requestId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }


}
