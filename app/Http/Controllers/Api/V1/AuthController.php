<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RefreshToken;
use App\Http\Requests\V1\SetupAccountRequest;
use App\Http\Requests\V1\VerifyEmailRequest;
use App\Http\Requests\V1\UploadDocumentRequest;
use App\Http\Requests\V1\CompleteProfileRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Responses\Response;
use App\Services\AuthServices;
use App\Models\User;
use Throwable;

class AuthController extends Controller
{
    private AuthServices $authServices;
    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->authServices->login($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message, 401);
        }
    }
    public function getProfile(): JsonResponse
    {
        try {
            $data = $this->authServices->getProfile();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message, 401);
        }
    }
    public function logout(): JsonResponse
    {
        try {
            $data = $this->authServices->logout(auth()->user());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }
    public function resendCode(Request $request)
    {
        try {
            $data = $this->authServices->resendCode($request->Email);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (\Throwable $th) {
            return Response::Error([], $th->getMessage());
        }
    }

    public function verifyCode(VerifyEmailRequest $request)
    {
        try {
            $data = $this->authServices->verifyCode($request->all());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (\Throwable $th) {
            return Response::Error([], $th->getMessage());
        }
    }

    public function refreshToken(RefreshToken $request): JsonResponse
    {
        try {

            $data = $this->authServices->refreshToken($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }

    public function setupAccount(SetupAccountRequest $request): JsonResponse
    {
        try {
            $data = $this->authServices->setupAccount(auth()->user(), $request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function uploadDocument(UploadDocumentRequest $request)
    {
        try {
            $data = $this->authServices->uploadDocument(auth()->user(), $request);
            return Response::success($data, 'تم رفع الملف بنجاح', 200);
        } catch (\Throwable $th) {
            return Response::Error([], $th->getMessage());
        }
    }
    public function completeProfile(CompleteProfileRequest $request)
    {
        try {
            $data = $this->authServices->completeProfile(auth()->user(), $request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (\Throwable $th) {
            return Response::Error([], $th->getMessage());
        }
    }

    public function submit()
    {
        try {
            $data = $this->authServices->submit(auth()->user());

            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (\Throwable $th) {
            return Response::Error([], $th->getMessage());
        }
    }
}
