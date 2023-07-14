<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('storageAsset')) {
    function storageAsset($value)
    {
        if ($value == null) {
            return;
        }

        return asset(Storage::url($value));
    }
}