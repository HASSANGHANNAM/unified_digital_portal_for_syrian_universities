<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UploadCollegeLogoRequest;
use App\Services\CollegeService;
use App\Http\Responses\Response;
use Throwable;
use Illuminate\Http\JsonResponse;

class CollegeController extends Controller
{
    public function __construct(
        private CollegeService $collegeService,
    ) {}

    public function uploadLogo(UploadCollegeLogoRequest $request, int $collegeId): JsonResponse
    {
        try {
            $result = $this->collegeService->uploadLogo($collegeId, $request->file('logo'));
            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 500);
        }
    }
}
