<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GetTeachingAssistantCoursesRequest;
use App\Http\Requests\V1\GetTeachingAssistantsRequest;
use App\Http\Requests\V1\GetTeachingAssistantUniversitiesRequest;
use App\Http\Responses\Response;
use App\Services\TeachingAssistantService;
use Illuminate\Http\JsonResponse;
use Throwable;

class TeachingAssistantController extends Controller
{
    public function __construct(private TeachingAssistantService $teachingAssistantService)
    {
        $this->middleware('auth:sanctum');
    }

    public function getUniversities(GetTeachingAssistantUniversitiesRequest $request): JsonResponse
    {
        try {
            $data = $this->teachingAssistantService->getUniversities($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getCourses(GetTeachingAssistantCoursesRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['per_page'] = (int) $request->input('per_page', 15);
            $validated['page'] = $request->filled('page') ? (int) $request->input('page') : null;

            $data = $this->teachingAssistantService->getCourses($validated);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function getTeachingAssistants(GetTeachingAssistantsRequest $request): JsonResponse
    {
        try {
            $filters = $request->validated();
            $result = $this->teachingAssistantService->getTeachingAssistants(
                $filters,
                $filters['per_page'] ?? 15
            );
            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
