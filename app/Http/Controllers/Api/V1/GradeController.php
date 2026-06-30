<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\GradeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\AddGradeRequest;
use App\Http\Requests\V1\GetAllGradesRequest;
use App\Http\Requests\V1\GetGradeAppealsRequest;
use App\Http\Requests\V1\ProcessAppealRequest;
use App\Http\Requests\V1\UpdateGradeRequest;
use App\Http\Requests\V1\AddGradesforonestudentRequest;
use App\Http\Responses\Response;
use Throwable;

class GradeController extends Controller
{
    private GradeService $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    public function getAllGrades(GetAllGradesRequest $getAllGradesRequest): JsonResponse
    {
        try {
            $data = $this->gradeService->getAllGrades($getAllGradesRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function addGrade(AddGradeRequest $addGradeRequest): JsonResponse
    {
        try {
            $data = $this->gradeService->addGrade($addGradeRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getGradeAppeals(GetGradeAppealsRequest $getGradeAppealsRequest): JsonResponse
    {
        try {
            $data = $this->gradeService->getGradeAppeals($getGradeAppealsRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function processAppeal(ProcessAppealRequest $processAppealRequest, int $appealId): JsonResponse
    {
        try {
            $data = $this->gradeService->processAppeal($processAppealRequest->validated(), $appealId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function getgrade(int $courseId): JsonResponse
    {
        try {
            $data = $this->gradeService->getgrade($courseId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function getAllMyGrades(): JsonResponse
    {
        try {
            $data = $this->gradeService->getAllMyGrades();
            return Response::success($data['data'],$data['message'],$data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function getCourseGrades(int $courseId, string $academicYear, int $semester): JsonResponse
    {
        try {
            $data = $this->gradeService->getCourseGrades(auth()->user(),$courseId, $academicYear, $semester);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function updateGrade(UpdateGradeRequest $request, int $studentCoursePartId): JsonResponse
    {
        try {
            $data = $this->gradeService->updateGrade(auth()->user(), $studentCoursePartId, $request->grade);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function addGradesforonestudent(AddGradesforonestudentRequest $request,int $courseId,string $academicYear,int $semester): JsonResponse
    {
    try{
        $data=$this->gradeService->addGradesforonestudent(auth()->user(),$courseId,$academicYear,$semester,$request->validated());
        return Response::success($data['data'],$data['message'],$data['code']);
    }catch(Throwable $th){
        return Response::Error([],$th->getMessage(),400);
    }
}








}
