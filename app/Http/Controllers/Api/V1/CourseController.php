<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GetCourseDetailsRequest;
use App\Http\Responses\Response;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Throwable;

class CourseController extends Controller
{
    public function __construct(private CourseService $courseService) {}

    public function getCourseDetails(GetCourseDetailsRequest $request): JsonResponse
    {
        try {
            $data = $this->courseService->getCourseDetails($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
