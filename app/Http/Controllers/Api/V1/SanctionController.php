<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\V1\StoreSanctionRequest;
use App\Http\Requests\V1\CreateSanctionTypeRequest;
use App\Http\Requests\V1\DeleteSanctionRequest;
use App\Http\Requests\V1\GetSanctionTypesRequest;
use App\Http\Requests\V1\GetSanctionsRequest;
use App\Http\Requests\V1\UpdateSanctionRequest;
use App\Http\Requests\V1\RespondSanctionRequest;
use App\Http\Responses\Response;
use App\Services\SanctionService;
use Throwable;

class SanctionController extends Controller
{
    private SanctionService $sanctionService;

    public function __construct(SanctionService $sanctionService)
    {
        $this->middleware('auth:sanctum');
        $this->sanctionService = $sanctionService;
    }

    public function index(GetSanctionTypesRequest $request): JsonResponse
    {
        try {
            $filters = [
                'name' => $request->input('name'),
                'reason' => $request->input('reason'),
            ];
            $perPage = (int) $request->input('per_page', 15);
            $perPage = $perPage > 0 ? $perPage : 15;
            $data = $this->sanctionService->listSanctionTypes($filters, $perPage);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getSanctions(GetSanctionsRequest $getSanctionsRequest): JsonResponse
    {
        try {
            $data = $this->sanctionService->getSanctions($getSanctionsRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function store(CreateSanctionTypeRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $data = $this->sanctionService->createSanctionType($validated);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function addSanction(StoreSanctionRequest $request): JsonResponse
    {
        try {
            $data = $this->sanctionService->storeSanction($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function updateSanction(UpdateSanctionRequest $updateSanctionRequest, int $sanctionId): JsonResponse
    {
        try {
            $data = $this->sanctionService->updateSanction($updateSanctionRequest->validated(), $sanctionId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function deleteSanction(DeleteSanctionRequest $deleteSanctionRequest, int $sanctionId): JsonResponse
    {
        try {
            $data = $this->sanctionService->deleteSanction($deleteSanctionRequest->validated(), $sanctionId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function getAllSanctions(): JsonResponse
    {
        try {
            $data = $this->sanctionService->getAllSanctions();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getSanctionDetails(int $sanctionId): JsonResponse
    {
        try {
            $data = $this->sanctionService->getSanctionDetails($sanctionId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function respondToSanction(RespondSanctionRequest $request, int $sanctionId): JsonResponse
    {
        try {
            $data = $this->sanctionService->respondToSanction($sanctionId, $request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
