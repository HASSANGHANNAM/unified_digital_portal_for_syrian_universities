<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateUserRequest;
use App\Http\Requests\V1\DeleteUserRequest;
use App\Http\Requests\V1\GetUserPermissionsRequest;
use App\Http\Requests\V1\GetUsersRequest;
use App\Http\Requests\V1\ToggleUserActivationRequest;
use App\Http\Requests\V1\UpdateUserRoleRequest;
use App\Http\Requests\V1\UploadSignatureRequest;
use App\Http\Responses\Response;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsers(GetUsersRequest $request): JsonResponse
    {
        try {
            $data = $this->userService->listUsers($request->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function addUser(CreateUserRequest $createUserRequest): JsonResponse
    {
        try {
            $data = $this->userService->createUser($createUserRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function updateUserRole(UpdateUserRoleRequest $updateUserRoleRequest, int $id): JsonResponse
    {
        try {
            $data = $this->userService->updateRole($updateUserRoleRequest->validated(), $id);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function deleteUser(DeleteUserRequest $deleteUserRequest, int $id): JsonResponse
    {
        try {
            $data = $this->userService->deleteUser($deleteUserRequest->validated(), $id);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getUserPermissions(GetUserPermissionsRequest $getUserPermissionsRequest, int $id): JsonResponse
    {
        try {
            $data = $this->userService->getPermissions($getUserPermissionsRequest->validated(), $id);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function toggleUserActivation(ToggleUserActivationRequest $toggleUserActivationRequest, int $id): JsonResponse
    {
        try {
            $data = $this->userService->toggleActivation($toggleUserActivationRequest->validated(), $id);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function uploadSignature(UploadSignatureRequest $uploadSignatureRequest): JsonResponse
    {
        try {
            $data = $this->userService->uploadSignature($uploadSignatureRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
    public function mySignature(): JsonResponse
    {
        try {
            $data = $this->userService->getMySignature();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
