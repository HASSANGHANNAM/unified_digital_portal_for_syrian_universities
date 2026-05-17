<?php

namespace App\Services;

use App\DTOs\InvoiceDTO;
use App\Repositories\Contracts\UserRepositoryInterface;

class PaymentService
{
      public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
    ) {}

    public function getInvoices(array $data): array
    {
        $message = 'عرض الفواتير المستحقة على الطالب.';
        $code = 200;
        // use App\DTOs\InvoiceDTO;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function payInvoice(array $data, int $invoiceId): array
    {
        $message = 'إنشاء عملية دفع وإرجاع رابط بوابة الدفع.';
        $code = 200;
        $data = array_merge($data, ["invoiceId" => $invoiceId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function paymentCallback(array $data): array
    {
        $message = 'Webhook لبوابة الدفع لتحديث حالة الدفع.';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function paymentStatus(array $data, int $paymentId): array
    {
        $message = 'متابعة حالة سداد فاتورة معينة.';
        $code = 200;
        $data = array_merge($data, ["paymentId" => $paymentId]);
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

}
