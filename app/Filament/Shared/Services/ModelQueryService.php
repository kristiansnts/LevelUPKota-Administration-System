<?php

namespace App\Filament\Shared\Services;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\User;

class ModelQueryService
{
    public static function getUserModel(): User
    {
        /**
         * @var \App\Models\User
         */
        $user = \Illuminate\Support\Facades\Auth::user();

        return $user;
    }

    /**
     * @return array<mixed>
     */
    public static function getProvinceOptions(): array
    {
        return Province::query()
            ->pluck('prov_name', 'prov_id')
            ->filter()
            ->toArray();
    }

    /**
     * @return array<mixed>
     */
    public static function getCityOptions(): array
    {
        return City::query()
            ->pluck('city_name', 'city_id')
            ->filter()
            ->toArray();
    }

    /**
     * @return array<mixed>
     */
    public static function getDistrictOptions(): array
    {
        return District::query()
            ->pluck('dis_name', 'dis_id')
            ->filter()
            ->toArray();
    }
}
