<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;

class AdminService
{
      public function __construct(
        private UserRepositoryInterface $userRepositoryInterface
    ) {}

    public function dashboardStats(array $data): array
    {
        $message = 'عرض إحصائيات عامة (أعداد المستخدمين، الطلبات، المدفوعات).';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

    public function getAuditLogs(array $data): array
    {
        $message = 'سجل العمليات والمراجعات (لوزارة التعليم أو مدير الجامعة).';
        $code = 200;
        $data = $data;
        return [
            'data' => $data,
            'message' => $message,
            'code' => $code,
        ];
    }

}
