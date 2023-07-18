<?php

use App\Http\Controllers\Sales\Auth\AuthController;
use App\Http\Controllers\Sales\DashboardController;
use App\Http\Controllers\Sales\PharmacyController;
use App\Http\Controllers\Sales\VisitController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('sales')->name('sales.')->group(function () {
    Route::get('/', function () {
        return redirect(route('sales.dashboard'));
    })->name('home');

    Route::middleware('guest:sales,admin')->group(function () {
        Route::get('login', [AuthController::class, 'index']);
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

    Route::middleware('auth:sales')->group(function () {

        Route::get('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('dashbaord', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('report', [DashboardController::class, 'report'])->name('report');

        Route::resource('pharmacy', PharmacyController::class);

        Route::resource('visit', VisitController::class);
    });
});
