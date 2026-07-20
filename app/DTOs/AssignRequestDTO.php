<?php

namespace App\DTOs;

class AssignRequestDTO
{
    public function __construct(
        public readonly int $requestId,
        public readonly string $status,
        public readonly string $message
    ) {}

    public function toArray(): array
    {
        return [
            'request_id' => $this->requestId,
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
