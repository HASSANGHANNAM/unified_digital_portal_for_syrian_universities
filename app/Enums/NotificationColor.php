<?php

namespace App\Enums;

enum NotificationColor: string
{
    case SUCCESS = 'Green';
    case DANGER = 'Red';
    case WARNING = 'Yellow';
    case INFO = 'Blue';
    case PRIMARY = 'Cyan';
    case SECONDARY = 'Gray';

    public function label(): string
    {
        return match ($this) {
            self::SUCCESS => 'نجاح',
            self::DANGER  => 'خطر',
            self::WARNING => 'تحذير',
            self::INFO    => 'معلومات',
            self::PRIMARY => 'رئيسي',
            self::SECONDARY => 'ثانوي',
        };
    }
}
