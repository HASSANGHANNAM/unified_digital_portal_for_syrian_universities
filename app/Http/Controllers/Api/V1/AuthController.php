<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RefreshToken;
use App\Http\Requests\V1\SetupAccountRequest;
use App\Http\Requests\V1\VerifyEmailRequest;
use App\Http\Requests\V1\UploadDocumentRequest;
use App\Http\Requests\V1\CompleteProfileRequest;
use App\Http\Requests\V1\ChangePasswordRequest;
use App\Http\Requests\V1\ResetPasswordRequest;
use App\Http\Requests\V1\EditProfileRequest;
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
    public function editProfile(EditProfileRequest $request): JsonResponse
    {
        try {
            $data = $this->authServices->editProfile($request);
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

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $data = $this->authServices->changePassword($request);
            return Response::success($data['data'],$data['message'],$data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }
    public function forgotPassword(): JsonResponse
    {
        try {
            $data = $this->authServices->forgotPassword();
            return Response::success($data['data'],$data['message'],$data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }
    public function verifyResetCode(VerifyEmailRequest $request): JsonResponse
    {
        try {
            $data = $this->authServices->verifyResetCode($request);
            return Response::success($data['data'],$data['message'],$data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {

            $data = $this->authServices->resetPassword($request);
            return Response::success($data['data'],$data['message'],$data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error([], $message);

        }
    }
}
