<?php

if (!function_exists('components')) {
    function components($key = null, $default = null)
    {
        if ($key == null) {
            return config("components", $default);
        }
        return config("components.$key", $default);
    }
}

if (!function_exists('components_full_dir')) {
    function components_full_dir()
    {
        return __DIR__;
    }
}

require_once 'Tools/Functions.php';
