<?php

use Illuminate\Support\Str;

if (!function_exists('isPlural')) {
    function isPlural($word)
    {
        return Str::plural($word) === $word;
    }
}

if (!function_exists('isSingular')) {
    function isSingular($word)
    {
        return Str::singular($word) === $word;
    }
}
