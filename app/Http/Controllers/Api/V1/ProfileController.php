<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\SetupAccountRequest;
use App\Http\Requests\V1\VerifyEmailRequest;
use App\Http\Requests\V1\UploadDocumentRequest;
use App\Http\Requests\V1\CompleteProfileRequest;
use App\Http\Responses\Response;
use Throwable;

class ProfileController extends Controller
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

        public function setupAccount(SetupAccountRequest $request): JsonResponse
    {
        try {
            $data = $this->profileService->setupAccount(auth()->user(), $request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function uploadDocument(UploadDocumentRequest $request)
    {
        try {
            $data = $this->profileService->uploadDocument(auth()->user(), $request);
            return Response::success($data, 'تم رفع الملف بنجاح', 200);
        } catch (\Throwable $th) {
            return Response::Error([], $th->getMessage());
        }
    }
    public function completeProfile(CompleteProfileRequest $request)
    {
        try {
            $data = $this->profileService->completeProfile(auth()->user(), $request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (\Throwable $th) {
            return Response::Error([], $th->getMessage());
        }
    }

    public function submit()
    {
        try {
            $data = $this->profileService->submit(auth()->user());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (\Throwable $th) {
            return Response::Error([], $th->getMessage());
        }
    }
}
