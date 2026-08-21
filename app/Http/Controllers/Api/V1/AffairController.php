<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\AffairsService;
use App\Http\Responses\Response;

class AffairController extends Controller
{
    private AffairsService $AffairsService;
    public function __construct(AffairsService $AffairsService)
    {
        $this->AffairsService = $AffairsService;
    }

    public function getCollegeCourses(): JsonResponse
    {
        try {
            $data = $this->AffairsService->getCollegeCourses();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message, 500);
        }
    }
    public function getDepartmentCourses($departmentId): JsonResponse
    {
        try {
            $data = $this->AffairsService->getDepartmentCourses($departmentId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message, 500);
        }
    }
}
