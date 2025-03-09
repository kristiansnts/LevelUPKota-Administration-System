<?php

namespace App\Helpers;

use App\Filament\Shared\Services\ModelQueryService;

class LabelHelper
{
    public static function cityMailLabel(): string
    {
        $user = ModelQueryService::getUserModel();
        $base = 'LUP';

        dump($user);

        // Get district or city name through relationships
        $workBase = $user->district?->dis_name ?? $user->city?->city_name ?? '';

        return sprintf('%s-%s', $base, $workBase);
    }
}
