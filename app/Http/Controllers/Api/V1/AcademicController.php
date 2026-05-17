<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AcademicService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\GetCourseStudentsRequest;
use App\Http\Requests\V1\GetGradesRequest;
use App\Http\Requests\V1\GetInstructorCoursesRequest;
use App\Http\Requests\V1\GetScheduleRequest;
use App\Http\Requests\V1\GetStudentCoursesRequest;
use App\Http\Requests\V1\GetTeachingAssistantsRequest;
use App\Http\Responses\Response;
use Throwable;

class AcademicController extends Controller
{
    private AcademicService $academicService;

    public function __construct(AcademicService $academicService)
    {
        $this->academicService = $academicService;
    }

    public function getSchedule(GetScheduleRequest $getScheduleRequest): JsonResponse
    {
        try {
            $data = $this->academicService->getSchedule($getScheduleRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getStudentCourses(GetStudentCoursesRequest $getStudentCoursesRequest): JsonResponse
    {
        try {
            $data = $this->academicService->getStudentCourses($getStudentCoursesRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getGrades(GetGradesRequest $getGradesRequest): JsonResponse
    {
        try {
            $data = $this->academicService->getGrades($getGradesRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getInstructorCourses(GetInstructorCoursesRequest $getInstructorCoursesRequest): JsonResponse
    {
        try {
            $data = $this->academicService->getInstructorCourses($getInstructorCoursesRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getTeachingAssistants(GetTeachingAssistantsRequest $getTeachingAssistantsRequest): JsonResponse
    {
        try {
            $data = $this->academicService->getTeachingAssistants($getTeachingAssistantsRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getCourseStudents(GetCourseStudentsRequest $getCourseStudentsRequest, int $courseId): JsonResponse
    {
        try {
            $data = $this->academicService->getCourseStudents($getCourseStudentsRequest->validated(), $courseId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

}
