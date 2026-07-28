<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GetStudentSanctionsRequest;
use App\Http\Responses\Response;
use App\Services\SanctionService;
use Illuminate\Http\JsonResponse;
use Throwable;

class SanctionControllerExtended extends Controller
{
    public function __construct(private SanctionService $sanctionService) {}

    public function getStudentSanctions(GetStudentSanctionsRequest $request): JsonResponse
    {
        try {
            $data = $this->sanctionService->getStudentSanctions($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
