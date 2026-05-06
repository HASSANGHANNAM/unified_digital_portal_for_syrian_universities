<?php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class StudentRegisteredNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public function __construct(public Student $student) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->payload($notifiable);
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return (new BroadcastMessage($this->payload($notifiable)))->on([
            new PrivateChannel('App.Models.User.'.$notifiable->id),
        ]);
    }

    public function broadcastAs(): string
    {
        return 'notification.created';
    }

    /**
     * @return array<string, mixed>
     */
    protected function payload(object $notifiable): array
    {
        return [
            'title' => 'تسجيل طالب جديد',
            'body' => 'تم تسجيل الطالب المرتبط بحسابك في النظام.',
            'student_id' => $this->student->id,
            'person_id' => $this->student->person_id,
            'type' => class_basename(static::class),
        ];
    }
}
