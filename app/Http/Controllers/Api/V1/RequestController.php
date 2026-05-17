<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\RequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\CancelRequestRequest;
use App\Http\Requests\V1\CreateRequestRequest;
use App\Http\Requests\V1\GetAllRequestsRequest;
use App\Http\Requests\V1\GetRequestDetailsRequest;
use App\Http\Requests\V1\GetStudentRequestsRequest;
use App\Http\Requests\V1\ReviewRequestRequest;
use App\Http\Responses\Response;
use Throwable;

class RequestController extends Controller
{
    private RequestService $requestService;

    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
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

    public function getRequestDetails(GetRequestDetailsRequest $getRequestDetailsRequest, int $requestId): JsonResponse
    {
        try {
            $data = $this->requestService->getRequestDetails($getRequestDetailsRequest->validated(), $requestId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function cancelRequest(CancelRequestRequest $cancelRequestRequest, int $requestId): JsonResponse
    {
        try {
            $data = $this->requestService->cancelRequest($cancelRequestRequest->validated(), $requestId);
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

}
