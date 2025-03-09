<?php

namespace App\Helpers;

class RouteHelper
{
    /**
     * Determine if the route name matches a given pattern.
     *
     * @param  mixed  ...$patterns
     *
     * @return bool
     */
    public static function isRouteName(...$patterns)
    {
        return request()->routeIs($patterns);
    }
}
