<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GetDoctorCoursesRequest;
use App\Http\Requests\V1\GetDoctorsRequest;
use App\Http\Requests\V1\GetDoctorUniversitiesRequest;
use App\Http\Responses\Response;
use App\Services\DoctorService;
use Illuminate\Http\JsonResponse;
use Throwable;

class DoctorController extends Controller
{
    public function __construct(private DoctorService $doctorService) {}

    public function getUniversities(GetDoctorUniversitiesRequest $request): JsonResponse
    {
        try {
            $data = $this->doctorService->getUniversities($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getCourses(GetDoctorCoursesRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['per_page'] = (int) $request->input('per_page', 15);
            $validated['page'] = $request->filled('page') ? (int) $request->input('page') : null;

            $data = $this->doctorService->getCourses($validated);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function getDoctors(GetDoctorsRequest $request): JsonResponse
    {
        try {
            $filters = $request->validated();
            $perPage = $filters['per_page'] ?? 15;
            $result = $this->doctorService->getDoctors($filters, $perPage);
            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
