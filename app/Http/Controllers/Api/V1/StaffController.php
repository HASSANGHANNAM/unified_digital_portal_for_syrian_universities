<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\GetStaffRequest;
use App\Http\Responses\Response;
use App\Services\StaffService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Throwable;

class StaffController extends Controller
{

    public function __construct(private StaffService $service) {}
    public function getStaff(GetStaffRequest $request): JsonResponse
    {
        try {
            $filters = $request->validated();
            $data = $this->service->getStaff(
                $filters,
                $filters['per_page'] ?? 15
            );
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }
}
