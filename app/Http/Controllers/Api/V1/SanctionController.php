<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SanctionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\AddSanctionRequest;
use App\Http\Requests\V1\DeleteSanctionRequest;
use App\Http\Requests\V1\GetSanctionsRequest;
use App\Http\Requests\V1\UpdateSanctionRequest;
use App\Http\Responses\Response;
use Throwable;

class SanctionController extends Controller
{
    private SanctionService $sanctionService;

    public function __construct(SanctionService $sanctionService)
    {
        $this->sanctionService = $sanctionService;
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

    public function addSanction(AddSanctionRequest $addSanctionRequest): JsonResponse
    {
        try {
            $data = $this->sanctionService->addSanction($addSanctionRequest->validated());
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

}
