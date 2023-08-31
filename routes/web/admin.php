<?php

use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\CompositionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Entity\AdminController;
use App\Http\Controllers\Admin\Entity\SalesController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\Report\DepositReportController;
use App\Http\Controllers\Admin\Report\OngoingRequestController;
use App\Http\Controllers\Admin\Report\PharmacyReportController;
use App\Http\Controllers\Admin\Report\SalesReportController;
use App\Http\Controllers\Admin\TestimoniController;
use App\Http\Controllers\Admin\VisitController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return redirect(route('admin.dashboard'));
    })->name('home');

    Route::middleware('guest:sales,admin')->group(function () {
        Route::get('login', [AuthController::class, 'index']);
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

    Route::middleware('auth:admin')->group(function () {

        Route::get('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('carousel', CarouselController::class);
        Route::post('carousel-status/{id}', [CarouselController::class, 'status'])->name('carousel.status');

        Route::resource('product', ProductController::class);
        Route::prefix('product-detail/{product}')->group(function () {
            Route::resource('certification', CertificateController::class);
            Route::resource('composition', CompositionController::class);
        });

        Route::get('pharmacy/export_pdf', [PharmacyController::class, 'export_pdf'])->name('pharmachy.export_pdf');
        Route::resource('pharmacy', PharmacyController::class);

        Route::resource('blog-category', BlogCategoryController::class);
        Route::resource('blog', BlogController::class);

        Route::resource('testimoni', TestimoniController::class);
        Route::post('testimoni-status/{id}', [TestimoniController::class, 'status'])->name('testimoni.status');

        Route::resource('visit', VisitController::class);
        Route::post('visit-status/{id}', [VisitController::class, 'status'])->name('visit.status');

        // Route::resource('ongoing-request', OngoingRequestController::class);
        // Route::post('ongoing-request-status/{ongoingRequest}', [OngoingRequestController::class, 'update_status'])->name('ongoing-request.status');

        // Route::resource('deposit-report', DepositReportController::class);
        // Route::post('deposit-report-status/{depositReport}', [DepositReportController::class, 'update_status'])->name('deposit-report.status');

        // Route::resource('sales-report', SalesReportController::class);
        // Route::resource('pharmacy-report', PharmacyReportController::class);

        // Route::get('pharmacy-report-log/{id}', [PharmacyReportController::class, 'log'])->name('pharmacy-report.log');

        Route::resource('purchase', PurchaseController::class);
        Route::post('purchase/{purchase}/change-status', [PurchaseController::class, 'changeStatus'])->name('purchase.status');

        Route::get('province', [AddressController::class, 'province_index'])->name('province.index');
        Route::post('province/{id}', [AddressController::class, 'province_status'])->name('province.status');

        Route::get('city', [AddressController::class, 'city_index'])->name('city.index');
        Route::post('city/{id}', [AddressController::class, 'city_status'])->name('city.status');

        // ENTITY
        Route::resource('sales', SalesController::class);
        Route::resource('admin', AdminController::class);
    });
});
