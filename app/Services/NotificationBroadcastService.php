<?php

namespace App\Services;

use App\Jobs\SendBroadcastChunkJob;
use App\Models\Advertisement;
use App\Models\AdvertisementAttachment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class NotificationBroadcastService
{
    public function broadcast(array $data, array $files): array
    {
        $title = $data['title'] ?? '';
        $message = $data['message'] ?? '';
        $type = $data['type'] ?? 'info';
        $chunkSize = (int) config('notification.chunk_size', 100);
        $maxRecipients = (int) config('notification.max_recipients', 0);

        $userIds = $this->resolveRecipientUserIds($data);
        if (empty($userIds)) {
            return [
                'success' => false,
                'message' => 'لا يوجد مستخدمون مستهدفون',
                'code' => 422,
            ];
        }

        if ($maxRecipients > 0 && count($userIds) > $maxRecipients) {
            return [
                'success' => false,
                'message' => 'عدد المستلمين يتجاوز الحد المسموح به',
                'code' => 422,
            ];
        }
        $advertisement = null;

        DB::transaction(function () use ($userIds, $title, $message, $type, $data, $files, &$advertisement) {
            $this->storeDatabaseNotifications($userIds, $title, $message, $type);
            $advertisement = Advertisement::create([
                'title'   => $data['title'],
                'message' => $data['message'],
                'user_id' => auth()->id(),
            ]);
            foreach (array_values($files) as $file) {
                $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                $path = "advertisement_attachments/{$advertisement->id}";
                Storage::disk('private')->putFileAs($path, $file, $filename);
                AdvertisementAttachment::create([
                    'advertisement_id' => $advertisement->id,
                    'name'             => $file->getClientOriginalName(),
                    'type'             => $file->getClientOriginalExtension(),
                    'path' => $path . '/' . $filename,
                ]);
            }
        });

        $chunks = array_chunk($userIds, $chunkSize);
        if ($advertisement == null) {
            return [
                'success' => false,
                'data' => [],
                'message' => ' لم يتم حفظ الاعلان',
                'code' => 403,
            ];
        }
        foreach ($chunks as $chunk) {
            SendBroadcastChunkJob::dispatch($chunk, $title, $message, $type, $advertisement->id ?? null);
        }

        return [
            'success' => true,
            'data' => [
                'total_recipients' => count($userIds),
                'jobs_dispatched' => count($chunks),
                'chunk_size' => $chunkSize,
            ],
            'message' => 'تم حفظ الإشعارات في قاعدة البيانات وبدء البث',
            'code' => 200,
        ];
    }

    protected function resolveRecipientUserIds(array $data): array
    {
        if (!empty($data['student_id'])) {
            $student = Student::query()->with('person.user')->find($data['student_id']);

            if (! $student) {
                throw new \RuntimeException('الطالب غير موجود');
            }

            $userId = $student->person?->user?->id;

            if (! $userId) {
                throw new \RuntimeException('لم يتم العثور على حساب مستخدم للطالب');
            }

            return [$userId];
        }

        $query = Student::query()->with('person.user');

        if (!empty($data['department_id'])) {
            $query->where('department_id', $data['department_id']);
        }

        if (!empty($data['college_id'])) {
            $query->where('college_id', $data['college_id']);
        }

        if (!empty($data['academic_year'])) {
            $query->where('enrollment_year', $data['academic_year']);
        }

        return $query->get()
            ->map(fn(Student $student) => $student->person?->user?->id)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function storeDatabaseNotifications(array $userIds, string $title, string $message, string $type): void
    {
        $timestamp = now();
        $rows = [];

        foreach ($userIds as $userId) {
            $rows[] = [
                'id' => (string) Str::uuid(),
                'type' => 'broadcast-notification',
                'notifiable_type' => User::class,
                'notifiable_id' => $userId,
                'data' => json_encode([
                    'title' => $title,
                    'message' => $message,
                    'type' => $type,
                ], JSON_UNESCAPED_UNICODE),
                'read_at' => null,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        try {
            DB::transaction(function () use ($rows) {
                foreach (array_chunk($rows, 1000) as $chunk) {
                    DB::table('notifications')->insert($chunk);
                }
            });
        } catch (Throwable $exception) {
            throw new \RuntimeException(
                'فشل تخزين الإشعارات في قاعدة البيانات: ' . $exception->getMessage(),
                0,
                $exception
            );
        }
    }
}
