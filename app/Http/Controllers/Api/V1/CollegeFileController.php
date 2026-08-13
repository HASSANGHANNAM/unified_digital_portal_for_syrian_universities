<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GetCollegeFilesRequest;
use App\Http\Requests\V1\UploadCollegeFileRequest;
use App\Http\Responses\Response;
use App\Services\CollegeFileService;
use Illuminate\Http\JsonResponse;
use Throwable;

class CollegeFileController extends Controller
{
    public function __construct(
        private CollegeFileService $fileService
    ) {}

    public function upload(UploadCollegeFileRequest $request): JsonResponse
    {
        try {
            $result = $this->fileService->uploadFile(
                $request->validated(),
                $request->file('file')
            );
            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function index(GetCollegeFilesRequest $request): JsonResponse
    {
        try {
            $result = $this->fileService->getAvailableFilesForStudent($request->validated());
            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $result = $this->fileService->getFileDetails($id);
            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function staff_college_files(GetCollegeFilesRequest $request, int $college_id): JsonResponse
    {
        try {
            if (!is_numeric($college_id)) {
                return Response::Error([], 'معرف الكلية يجب أن يكون رقماً صحيحاً.', 400);
            }
            $college_id = \App\Models\College::find($college_id);
            if (!$college_id) {
                return Response::Error([], 'الكلية غير موجودة.', 404);
            }
            $result = $this->fileService->getAvailableFilesForStaff($request->validated(), $college_id);
            return Response::success($result['data'], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
