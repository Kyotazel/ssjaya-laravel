<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {

        $path = $request->path();

        if (Auth::guard('sales')->check()) {
            return $request->expectsJson() ? null : route('sales.dashboard');
        }

        if (Auth::guard('admin')->check()) {
            return $request->expectsJson() ? null : route('admin.dashboard');
        }

        if (strpos($path, 'admin') !== false) {
            return route('admin.login');
        }

        if (strpos($path, 'sales') !== false) {
            return route('sales.login');
        }

        return $request->expectsJson() ? null : route('homepage');
    }
}
