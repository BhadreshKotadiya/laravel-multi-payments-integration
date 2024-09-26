<?php

if (!function_exists('is_premium_subscriber')) {
    function is_premium_subscriber()
    {
        return auth()->check() && auth()->user()->subscribed('prod_Qs2Sz2RupWFjAS');
    }
}
