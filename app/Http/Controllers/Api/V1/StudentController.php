<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GetCollegeStudentsRequest;
use App\Http\Responses\Response;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Throwable;

class StudentController extends Controller
{
    public function __construct(private StudentService $studentService) {}

    public function getCollegeStudents(GetCollegeStudentsRequest $request): JsonResponse
    {
        try {
            $data = $this->studentService->getCollegeStudents($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
