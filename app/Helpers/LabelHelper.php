<?php

namespace App\Helpers;

use App\Filament\Shared\Services\ModelQueryService;
use App\Models\CityCode;

class LabelHelper
{
    public static function cityMailLabel(): string
    {
        $user = ModelQueryService::getUserModel();
        $base = 'LUP';

        $districtName = '';
        $cityName = '';

        if ($user->district_id) {
            $districtCode = CityCode::where('district_id', $user->district_id)->first();
            $districtName = $districtCode ? $districtCode->code : '';
        }

        if ($user->city_id && empty($districtName)) {
            $cityCode = CityCode::where('city_id', $user->city_id)->first();
            $cityName = $cityCode ? $cityCode->code : '';
        }

        $workBase = $districtName ?: $cityName;

        return sprintf('%s-%s', $base, $workBase);
    }
}
