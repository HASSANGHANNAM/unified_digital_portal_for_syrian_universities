<?php

namespace App\Console\Commands;

use App\Notifications\ImportantAnnouncementNotification;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendWeeklyReminderNotifications extends Command
{
    protected $signature = 'notifications:weekly-reminder {--role=student : Role name (custom account_role pivot)}';

    protected $description = 'يرسل تذكيراً أسبوعياً عبر NotificationService (مثال للجدولة).';

    public function handle(NotificationService $notificationService): int
    {
        $role = (string) $this->option('role');

        $notificationService->sendToRole(
            $role,
            new ImportantAnnouncementNotification(
                'تذكير أسبوعي',
                'يرجى مراجعة بوابتكم الرقمية والتحقق من الطلبات والمواعيد المعلنة.',
                null
            )
        );

        $this->info('Weekly reminder dispatched for role: '.$role);

        return self::SUCCESS;
    }
}
