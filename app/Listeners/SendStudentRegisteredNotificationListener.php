<?php

namespace App\Listeners;

use App\Events\StudentRegistered;
use App\Models\User;
use App\Notifications\StudentRegisteredNotification;
use App\Services\NotificationService;

class SendStudentRegisteredNotificationListener
{
    public function __construct(protected NotificationService $notificationService) {}

    public function handle(StudentRegistered $event): void
    {
        $student = $event->student;

        if (! $student->person_id) {
            return;
        }

        $user = User::query()->where('person_id', $student->person_id)->first();

        if (! $user) {
            return;
        }

        $this->notificationService->sendToUser($user, new StudentRegisteredNotification($student));
    }
}
