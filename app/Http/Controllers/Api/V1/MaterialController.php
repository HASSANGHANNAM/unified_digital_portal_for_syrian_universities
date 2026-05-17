<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\MaterialService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\DeleteMaterialRequest;
use App\Http\Requests\V1\DownloadMaterialRequest;
use App\Http\Requests\V1\GetMaterialsRequest;
use App\Http\Requests\V1\UpdateMaterialRequest;
use App\Http\Requests\V1\UploadMaterialRequest;
use App\Http\Responses\Response;
use Throwable;

class MaterialController extends Controller
{
    private MaterialService $materialService;

    public function __construct(MaterialService $materialService)
    {
        $this->materialService = $materialService;
    }

    public function getMaterials(GetMaterialsRequest $getMaterialsRequest, int $courseId): JsonResponse
    {
        try {
            $data = $this->materialService->getMaterials($getMaterialsRequest->validated(), $courseId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function uploadMaterial(UploadMaterialRequest $uploadMaterialRequest, int $courseId): JsonResponse
    {
        try {
            $data = $this->materialService->uploadMaterial($uploadMaterialRequest->validated(), $courseId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function updateMaterial(UpdateMaterialRequest $updateMaterialRequest, int $materialId): JsonResponse
    {
        try {
            $data = $this->materialService->updateMaterial($updateMaterialRequest->validated(), $materialId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function deleteMaterial(DeleteMaterialRequest $deleteMaterialRequest, int $materialId): JsonResponse
    {
        try {
            $data = $this->materialService->deleteMaterial($deleteMaterialRequest->validated(), $materialId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function downloadMaterial(DownloadMaterialRequest $downloadMaterialRequest, int $materialId): JsonResponse
    {
        try {
            $data = $this->materialService->downloadMaterial($downloadMaterialRequest->validated(), $materialId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

}
