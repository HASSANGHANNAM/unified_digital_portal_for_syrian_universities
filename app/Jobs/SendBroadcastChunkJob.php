<?php

namespace App\Jobs;

use App\Events\SendCustomNotification;
use App\Models\AdvertisementStudent;
use App\Models\FailedBroadcastJob;
use App\Models\Student;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendBroadcastChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $userIds;
    public string $title;
    public string $message;
    public string $type;
    public ?int $failedBroadcastJobId;
    public ?int $advertisementId;

    public function __construct(
        array $userIds,
        string $title,
        string $message,
        string $type,
        ?int $failedBroadcastJobId = null,
        ?int $advertisementId = null
    ) {
        $this->userIds = array_values(array_unique(array_map('intval', $userIds)));
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->failedBroadcastJobId = $failedBroadcastJobId;
        $this->advertisementId = $advertisementId;
    }

    public function handle(): void
    {
        $users = User::query()->whereIn('id', $this->userIds)->get();
        $failedUserIds = [];

        foreach ($users as $user) {
            if ($this->advertisementId) {
                $u = User::with('person.student')->find($user->id);
                $student = $u->person->student;
                if ($student) {
                    AdvertisementStudent::firstOrCreate([
                        'advertisement_id' => $this->advertisementId,
                        'student_id'       => $student->id,
                    ]);
                }
            }

            try {
                event(new SendCustomNotification($user, $this->title, $this->message, $this->type));
            } catch (Throwable $exception) {
                $failedUserIds[] = $user->id;

                Log::error('Broadcast chunk user delivery failed.', [
                    'user_id' => $user->id,
                    'error'   => $exception->getMessage(),
                ]);
            }
        }

        if (!empty($failedUserIds)) {
            foreach ($failedUserIds as $userId) {
                SendSingleUserRetryJob::dispatch(
                    $userId,
                    $this->title,
                    $this->message,
                    $this->type,
                    $this->advertisementId
                );
            }
        }

        if ($this->failedBroadcastJobId !== null) {
            FailedBroadcastJob::query()->whereKey($this->failedBroadcastJobId)->delete();
        }
    }


    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function failed(Throwable $exception): void
    {
        $record = null;

        if ($this->failedBroadcastJobId !== null) {
            $record = FailedBroadcastJob::query()->find($this->failedBroadcastJobId);
        }

        if ($record) {
            $record->update([
                'user_ids' => $this->userIds,
                'title' => $this->title,
                'message' => $this->message,
                'type' => $this->type,
                'attempts' => $record->attempts + 1,
                'last_attempt_at' => now(),
            ]);
        } else {
            FailedBroadcastJob::query()->create([
                'user_ids' => $this->userIds,
                'title' => $this->title,
                'message' => $this->message,
                'type' => $this->type,
                'attempts' => 1,
                'last_attempt_at' => now(),
            ]);
        }

        Log::error('Broadcast chunk failed after all attempts (unexpected).', [
            'user_ids' => $this->userIds,
            'error' => $exception->getMessage(),
        ]);
    }
}
