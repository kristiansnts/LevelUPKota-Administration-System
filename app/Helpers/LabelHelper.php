<?php

namespace App\Helpers;

use App\Filament\Shared\Services\ModelQueryService;

class LabelHelper
{
    public static function cityMailLabel(): string
    {
        $user = ModelQueryService::getUserModel();
        $base = 'LUP';

        /**
         * @var string $districtName
         */
        $districtName = $user->district->dis_name ?? '';
        /**
         * @var string $cityName
         */
        $cityName = $user->city->city_name ?? '';

        $workBase = $districtName ?: $cityName;

        return sprintf('%s-%s', $base, $workBase);
    }
}
