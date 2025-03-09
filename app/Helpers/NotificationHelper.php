<?php

namespace App\Helpers;

use Filament\Notifications\Notification;

class NotificationHelper
{
    public static function success(string $message): void
    {
        Notification::make()
            ->title($message)
            ->success()
            ->send();
    }

    public static function error(string $message): void
    {
        Notification::make()
            ->title($message)
            ->danger()
            ->send();
    }

    public static function warning(string $message): void
    {
        Notification::make()
            ->title($message)
            ->warning()
            ->send();
    }

    public static function info(string $message): void
    {
        Notification::make()
            ->title($message)
            ->info()
            ->send();
    }
}
