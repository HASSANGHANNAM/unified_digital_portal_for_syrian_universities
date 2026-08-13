<?php

namespace App\DTOs;

use App\Models\UserSignature;

class SignatureDTO2
{
    public static function fromModel(UserSignature $signature): array
    {

        return [
            'id'             => $signature->id,
            'signature_uuid' => $signature->signature_uuid,
            'url'            => '/api/V1/signatures/view/' . $signature->signature_uuid,
            'created_at'     => $signature->created_at->toDateTimeString(),
        ];
    }
}
