<?php

namespace App\Filament\Shared\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ResourceScopeService
{
    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     */
    public static function userScope(Builder $query, ?string $columnToPluck = null): mixed
    {
        $user = ModelQueryService::getUserModel();

        // Always apply city_id if user has it
        if ($user->city_id) {
            $query->where('city_id', $user->city_id);
        }

        // Apply district_id condition if user has it
        if ($user->district_id) {
            $query->where('district_id', $user->district_id);
        }

        return $columnToPluck ? $query->pluck($columnToPluck) : $query;
    }
}
