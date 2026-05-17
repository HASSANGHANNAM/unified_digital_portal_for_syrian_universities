<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AdminService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\DashboardStatsRequest;
use App\Http\Requests\V1\GetAuditLogsRequest;
use App\Http\Responses\Response;
use Throwable;

class AdminController extends Controller
{
    private AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function dashboardStats(DashboardStatsRequest $dashboardStatsRequest): JsonResponse
    {
        try {
            $data = $this->adminService->dashboardStats($dashboardStatsRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function getAuditLogs(GetAuditLogsRequest $getAuditLogsRequest): JsonResponse
    {
        try {
            $data = $this->adminService->getAuditLogs($getAuditLogsRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

}
