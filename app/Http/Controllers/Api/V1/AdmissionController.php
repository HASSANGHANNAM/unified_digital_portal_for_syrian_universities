<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AdmissionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\ApplyForAdmissionRequest;
use App\Http\Requests\V1\GetApplicationsRequest;
use App\Http\Requests\V1\GetMyApplicationRequest;
use App\Http\Requests\V1\ReviewApplicationRequest;
use App\Http\Responses\Response;
use Throwable;

class AdmissionController extends Controller
{
    private AdmissionService $admissionService;

    public function __construct(AdmissionService $admissionService)
    {
        $this->admissionService = $admissionService;
    }

    public function applyForAdmission(ApplyForAdmissionRequest $applyForAdmissionRequest): JsonResponse
    {
        try {
            $data = $this->admissionService->applyForAdmission($applyForAdmissionRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getMyApplication(GetMyApplicationRequest $getMyApplicationRequest): JsonResponse
    {
        try {
            $data = $this->admissionService->getMyApplication($getMyApplicationRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getApplications(GetApplicationsRequest $getApplicationsRequest): JsonResponse
    {
        try {
            $data = $this->admissionService->getApplications($getApplicationsRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function reviewApplication(ReviewApplicationRequest $reviewApplicationRequest, int $id): JsonResponse
    {
        try {
            $data = $this->admissionService->reviewApplication($reviewApplicationRequest->validated(), $id);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

}
