<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\SendLoginSuccessNotification;
use Throwable;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\Response;
use App\Services\StudyPlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudyPlanController extends Controller
{
    public function __construct(private StudyPlanService $studyPlanService) {}

    // عرض خطة الدراسة الكاملة
    public function getStudyPlan(): JsonResponse
    {
        try {
            $data = $this->studyPlanService->getStudyPlan();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    // عرض المواد للسنة الحالية
    public function getCurrentYearCourses(): JsonResponse
    {
        try {
            $data = $this->studyPlanService->getCurrentYearCourses();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    // عرض المواد للفصل الحالي
    public function getCurrentSemesterCourses(): JsonResponse
    {
        try {
            $data = $this->studyPlanService->getCurrentSemesterCourses();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    // عرض المواد المكتملة
    public function getCompletedCourses(): JsonResponse
    {
        try {
            Log::info('controller ' . auth()->user()->id);
            event(new SendLoginSuccessNotification(auth()->user(), 'Welcome back! You have successfully logged in.'));
            $data = $this->studyPlanService->getCompletedCourses();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    // عرض المواد المتبقية
    public function getRemainingCourses(): JsonResponse
    {
        try {
            $data = $this->studyPlanService->getRemainingCourses();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    // عرض التقدم الأكاديمي
    public function getAcademicProgress(): JsonResponse
    {
        try {
            $data = $this->studyPlanService->getAcademicProgress();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    // عرض تفاصيل مادة معينة
    public function getCourseDetails(int $courseId): JsonResponse
    {
        try {
            $data = $this->studyPlanService->getCourseDetails($courseId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    // عرض المواد لسنة معينة
    public function getYearCourses(int $year): JsonResponse
    {
        try {
            $data = $this->studyPlanService->getYearCourses($year);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    // عرض المواد لفصل معين
    public function searchCourses(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'search' => 'required|string|min:1',
            ]);
            $data = $this->studyPlanService->searchCourses($request->search);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
