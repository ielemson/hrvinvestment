<?php

if (!function_exists('isActive')) {
    function isActive($routes)
    {
        foreach ((array)$routes as $route) {
            if (request()->routeIs($route)) {
                return 'active';
            }
        }
        return '';
    }
}
