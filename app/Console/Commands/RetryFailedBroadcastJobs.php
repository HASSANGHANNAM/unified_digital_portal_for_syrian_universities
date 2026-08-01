<?php

namespace App\Console\Commands;

use App\Jobs\SendBroadcastChunkJob;
use App\Models\FailedBroadcastJob;
use Illuminate\Console\Command;

class RetryFailedBroadcastJobs extends Command
{
    protected $signature = 'broadcast:retry-failed';

    protected $description = 'إعادة محاولة بث الإشعارات الجماعية الفاشلة من جدول failed_broadcast_jobs.';

    public function handle(): int
    {
        $retryCutoff = now()->subHour();
        $maxAttempts = 4;
        $dispatched = 0;

        FailedBroadcastJob::query()
            ->where('attempts', '<', $maxAttempts)
            ->where(function ($query) use ($retryCutoff): void {
                $query->whereNull('last_attempt_at')
                    ->orWhere('last_attempt_at', '<=', $retryCutoff);
            })
            ->orderBy('id')
            ->chunkById(100, function ($jobs) use (&$dispatched): void {
                foreach ($jobs as $job) {
                    $job->forceFill(['last_attempt_at' => now()])->save();

                    SendBroadcastChunkJob::dispatch(
                        $job->user_ids,
                        $job->title,
                        $job->message,
                        $job->type,
                        $job->id
                    );

                    $dispatched++;
                }
            });

        $this->info('Dispatched ' . $dispatched . ' failed broadcast job(s) for retry.');

        return self::SUCCESS;
    }
}
