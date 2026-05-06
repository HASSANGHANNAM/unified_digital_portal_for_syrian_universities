<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\Log;

trait HasLogging
{
    protected function logInfo(string $message, array $context = []): void
    {
        Log::info($message, $context);
    }

    protected function logWarning(string $message, array $context = []): void
    {
        Log::warning($message, $context);
    }

    protected function logError(string $message, array $context = []): void
    {
        Log::error($message, $context);
    }
}
