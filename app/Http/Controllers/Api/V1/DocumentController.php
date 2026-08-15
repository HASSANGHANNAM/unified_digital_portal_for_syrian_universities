<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DocumentService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\AddDocumentRequest;
use App\Http\Requests\V1\GetDocumentsRequest;
use App\Http\Requests\V1\StoreLectureRequest;
use App\Http\Requests\V1\UpdateLectureRequest;
use App\Http\Responses\Response;
use Throwable;

class DocumentController extends Controller
{
    private DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function addDocument(AddDocumentRequest $addDocumentRequest, int $studentId): JsonResponse
    {
        try {
            $data = $this->documentService->addDocument($addDocumentRequest->validated(), $studentId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getDocuments(GetDocumentsRequest $getDocumentsRequest, int $studentId): JsonResponse
    {
        try {
            $data = $this->documentService->getDocuments($getDocumentsRequest->validated(), $studentId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function uploadLecture(StoreLectureRequest $request): JsonResponse
    {
        try {
            $data = $this->documentService->uploadLecture($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function lecturesByCoursePart(int $coursePartsId): JsonResponse
    {
        try {
            $data = $this->documentService->getLecturesByCoursePart($coursePartsId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (\Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function deleteLecture(int $id): JsonResponse
    {
        try {
            $result = $this->documentService->deleteLecture($id);
            return Response::success([], $result['message'], $result['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function updateLecture(UpdateLectureRequest $request, int $id): JsonResponse
    {
        try {
            $data = $this->documentService->updateLecture($id, $request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
