<?php

namespace App\DTOs;

use App\Models\UserSignature;

class SignatureDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $userId,
        public readonly string $uuid,
        public readonly string $createdAt,
        public readonly ?string $userName = null, // اختياري لعرض اسم الموقع
    ) {}

    /**
     * إنشاء DTO من Model
     */
    public static function fromModel(UserSignature $signature): self
    {
        return new self(
            id: $signature->id,
            userId: $signature->user_id,
            uuid: $signature->signature_uuid,
            createdAt: $signature->created_at->toISOString(),
            userName: $signature->user?->username ?? null, // إذا كانت العلاقة موجودة
        );
    }

    /**
     * تحويل DTO إلى مصفوفة للـ API
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'uuid' => $this->uuid,
            'created_at' => $this->createdAt,
            'user_name' => $this->userName ?? '',
        ];
    }
}
