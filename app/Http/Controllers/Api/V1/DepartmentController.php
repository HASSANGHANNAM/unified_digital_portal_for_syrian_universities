<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GetDepartmentsRequest;
use App\Http\Requests\V1\GetUniversitiesRequest;
use App\Http\Requests\V1\StoreCollegeDeanRequest;
use App\Http\Requests\V1\StoreDepartmentHeadRequest;
use App\Http\Requests\V1\UpdateUniversityRequest;
use App\Services\DepartmentService;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Response;
use Throwable;

class DepartmentController extends Controller
{
    private DepartmentService $service;

    public function __construct(DepartmentService $service)
    {
        $this->service = $service;
    }
    public function getDepartments(GetDepartmentsRequest $request): JsonResponse
    {
        try {
            $filters = $request->validated();
            $result = $this->service->getDepartments($filters);
            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function getUniversities(GetUniversitiesRequest $request): JsonResponse
    {
        try {
            $filters = $request->validated();
            $result = $this->service->getUniversities($filters);
            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function updateUniversity(UpdateUniversityRequest $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $result = $this->service->updateUniversity($id, $validated);

            if ($result['code'] !== 200) {
                return Response::Error([], $result['message'], $result['code']);
            }

            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function store(StoreCollegeDeanRequest $request, int $collegeId): JsonResponse
    {
        try {
            $validated = $request->validated();
            $result = $this->service->store($validated, $collegeId);

            if ($result['code'] !== 200) {
                return Response::Error([], $result['message'], $result['code']);
            }

            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function storeDepartmentHeads(StoreDepartmentHeadRequest $request, int $departmentId): JsonResponse
    {
        try {
            $validated = $request->validated();
            $result = $this->service->storeDepartmentHeads($departmentId, $validated);
            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
