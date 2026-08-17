<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AddStudentSuggestions;
use App\Http\Requests\V1\GetCollegeStudentsRequest;
use App\Http\Requests\V1\GetCollegeSuggestionsRequest;
use App\Http\Requests\V1\GetStudentSuggestions;
use App\Http\Requests\V1\UpdateSuggestionStatusRequest;
use App\Http\Requests\V1\ImportStudentsRequest;
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
    public function addStudentSuggestion(AddStudentSuggestions $request): JsonResponse
    {
        try {
            $data = $this->studentService->addStudentSuggestion($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function getStudentSuggestions(GetStudentSuggestions $request): JsonResponse
    {
        try {
            $data = $this->studentService->getStudentSuggestions($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function collegeSuggestions(GetCollegeSuggestionsRequest $request): JsonResponse
    {
        try {
            $data = $this->studentService->collegeSuggestions($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function updateSuggestionStatus(UpdateSuggestionStatusRequest $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['id'] = $id;
            $data = $this->studentService->updateSuggestionStatus($validated);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function importStudents(ImportStudentsRequest $request): JsonResponse
    {
        try {
            $data = $this->studentService->importStudents($request->file('file'));
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
