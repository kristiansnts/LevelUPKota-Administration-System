<?php

namespace App\Helpers;

class EnvironmentHelper
{
    public static function isProduction(): bool
    {
        return env('APP_ENV') === 'production';
    }

    public static function isDevelopment(): bool
    {
        return env('APP_ENV') === 'development';
    }

    public static function isLocal(): bool
    {
        return env('APP_ENV') === 'local';
    }
}
