<?php

namespace App\Services\Traits;

use App\Models\Request;

trait RequestValidationTrait
{
    public function isRequestCompleted(int $requestId): bool
    {
        $request = Request::find($requestId);
        if (!$request) {
            return false;
        }
        return $request->status === 'completed';
    }

    public function areAllSignaturesApproved(int $requestId): bool
    {
        $request = Request::with('requestUsers')->find($requestId);
        if (!$request) {
            return false;
        }

        if ($request->requestUsers->isEmpty()) {
            return false;
        }

        return $request->requestUsers->every(function ($signature) {
            return $signature->status === 'approved';
        });
    }
    public function isRequestFullyApproved(int $requestId): bool
    {
        return $this->isRequestCompleted($requestId) && $this->areAllSignaturesApproved($requestId);
    }
}
