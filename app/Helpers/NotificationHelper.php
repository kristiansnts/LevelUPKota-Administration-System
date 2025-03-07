<?php

namespace App\Helpers;

use Filament\Notifications\Notification;

class NotificationHelper
{
    public static function success(string $message): Notification
    {
        return Notification::make()
            ->title($message)
            ->success();
    }

    public static function error(string $message): Notification
    {
        return Notification::make()
            ->title($message)
            ->danger();
    }

    public static function warning(string $message): Notification
    {
        return Notification::make()
            ->title($message)
            ->warning();
    }

    public static function info(string $message): Notification
    {
        return Notification::make()
            ->title($message)
            ->info();
    }
}
