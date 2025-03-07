<?php

namespace App\Helpers;

use App\Filament\Shared\Services\ModelQueryService;

class RolesHelper
{
    public static function isSuperAdmin(): bool
    {
        /**
         * @var \App\Models\User
         */
        $user = ModelQueryService::getUserModel();

        return $user->hasRole('super_admin');
    }

    public static function isAdmin(): bool
    {
        /**
         * @var \App\Models\User
         */
        $user = ModelQueryService::getUserModel();

        return $user->hasRole('admin');
    }

    public static function isGuest(): bool
    {
        /**
         * @var \App\Models\User
         */
        $user = ModelQueryService::getUserModel();

        return $user->hasRole('guest');
    }
}
