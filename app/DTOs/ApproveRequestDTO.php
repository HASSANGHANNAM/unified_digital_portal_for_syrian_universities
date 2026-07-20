<?php

namespace App\DTOs;

class ApproveRequestDTO
{
    public function __construct(
        public readonly int $requestId,
        public readonly string $status,
        public readonly string $decision,
        public readonly string $message
    ) {}

    public function toArray(): array
    {
        return [
            'request_id' => $this->requestId,
            'status' => $this->status,
            'decision' => $this->decision,
            'message' => $this->message,
        ];
    }
}
