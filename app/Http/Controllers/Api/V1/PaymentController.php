<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\V1\GetInvoicesRequest;
use App\Http\Requests\V1\PayInvoiceRequest;
use App\Http\Requests\V1\PaymentCallbackRequest;
use App\Http\Requests\V1\PaymentStatusRequest;
use App\Http\Responses\Response;
use Throwable;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function getInvoices(GetInvoicesRequest $getInvoicesRequest): JsonResponse
    {
        try {
            $data = $this->paymentService->getInvoices($getInvoicesRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function payInvoice(PayInvoiceRequest $payInvoiceRequest, int $invoiceId): JsonResponse
    {
        try {
            $data = $this->paymentService->payInvoice($payInvoiceRequest->validated(), $invoiceId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function paymentCallback(PaymentCallbackRequest $paymentCallbackRequest): JsonResponse
    {
        try {
            $data = $this->paymentService->paymentCallback($paymentCallbackRequest->validated());
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

    public function paymentStatus(PaymentStatusRequest $paymentStatusRequest, int $paymentId): JsonResponse
    {
        try {
            $data = $this->paymentService->paymentStatus($paymentStatusRequest->validated(), $paymentId);
            return Response::success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            return Response::Error([], $th->getMessage(), 400);
        }
    }

}
