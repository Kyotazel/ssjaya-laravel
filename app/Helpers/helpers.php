<?php

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

if (!function_exists('authUser')) {
    function authUser()
    {
        return Auth::user();
    }
}
