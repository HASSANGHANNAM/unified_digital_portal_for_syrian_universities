<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\AddUserRequest;
use App\Http\Requests\V1\DeleteUserRequest;
use App\Http\Requests\V1\GetUserPermissionsRequest;
use App\Http\Requests\V1\ToggleUserActivationRequest;
use App\Http\Requests\V1\UpdateUserRoleRequest;
use App\Http\Responses\Response;
use Throwable;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsers(): JsonResponse
    {
        try {
            $data = $this->userService->getUsers();
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function addUser(AddUserRequest $addUserRequest): JsonResponse
    {
        try {
            $data = $this->userService->addUser($addUserRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function updateUserRole(UpdateUserRoleRequest $updateUserRoleRequest, int $id): JsonResponse
    {
        try {
            $data = $this->userService->updateUserRole($updateUserRoleRequest->validated(), $id);
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
            $data = $this->userService->getUserPermissions($getUserPermissionsRequest->validated(), $id);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function toggleUserActivation(ToggleUserActivationRequest $toggleUserActivationRequest, int $id): JsonResponse
    {
        try {
            $data = $this->userService->toggleUserActivation($toggleUserActivationRequest->validated(), $id);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

}
