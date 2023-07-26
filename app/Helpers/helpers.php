<?php

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

if (!function_exists('storageAsset')) {
    function storageAsset($value)
    {
        if ($value == null) {
            return;
        }

        return asset(Storage::url($value));
    }
}

if (!function_exists('carbonParse')) {
    function carbonParse($value)
    {
        return Carbon::parse($value);
    }
}
