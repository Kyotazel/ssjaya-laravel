<?php

use App\Http\Controllers\Sales\Auth\AuthController;
use App\Http\Controllers\Sales\DashboardController;
use App\Http\Controllers\Sales\PharmacyController;
use App\Http\Controllers\Sales\PurchaseController;
use App\Http\Controllers\Sales\Report\DepositReportController;
use App\Http\Controllers\Sales\Report\OngoingRequestController;
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
        Route::resource('pharmacy', PharmacyController::class);
        Route::resource('visit', VisitController::class);

        // Route::resource('ongoing-request', OngoingRequestController::class);
        // Route::resource('deposit-report', DepositReportController::class);

        Route::resource('purchase', PurchaseController::class);
        Route::get('purchase-archived/', [PurchaseController::class, 'archived'])->name('purchase.archived');
        Route::post('purchase/{purchase}/white', [PurchaseController::class, 'uploadWhite'])->name('purchase.upload-white');
        Route::post('purchase/{purchase}/yellow', [PurchaseController::class, 'uploadYellow'])->name('purchase.upload-yellow');
        Route::get('purchase/{purchase}/white', [PurchaseController::class, 'checkWhite'])->name('purchase.check-white');
        Route::get('purchase/{purchase}/yellow', [PurchaseController::class, 'checkYellow'])->name('purchase.check-yellow');
        Route::get('purchase/{purchase}/bill', [PurchaseController::class, 'bill'])->name('purchase.bill');
        Route::get('purchase/{purchase}/bill/create', [PurchaseController::class, 'billCreate'])->name('purchase.bill.create');
        Route::post('purchase/{purchase}/bill/store', [PurchaseController::class, 'billStore'])->name('purchase.bill.store');
    });
});
