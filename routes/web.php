<?php

use App\Http\Controllers\DatamasterController;
use App\Http\Controllers\Landing\HomeController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('ping', function () {
    $pdf = Pdf::loadView('pdf.purchase-report');
    return $pdf->download('purchase-report.pdf');
});

Route::get('/', [HomeController::class, 'index'])->name('homepage');
Route::get('about-us', [HomeController::class, 'aboutUs'])->name('about-us');
Route::get('consultation', [HomeController::class, 'consultation'])->name('consultation');
Route::get('product', [HomeController::class, 'product'])->name('product');
Route::get('product/{url}', [HomeController::class, 'productDetail'])->name('product.detail');
Route::get('article', [HomeController::class, 'article'])->name('article');
Route::get('article/{url}', [HomeController::class, 'articleDetail'])->name('article.detail');

Route::get('/list-mitra/{name}', [HomeController::class, 'listMitra'])->name('list-mitra');
Route::post('/list-mitra', [HomeController::class, 'listApotek'])->name('list-apotek');

Route::get('city/{id}', [DatamasterController::class, 'city'])->name('city');
Route::get('pharmacy/{id}', [DatamasterController::class, 'pharmacy'])->name('pharmacy');
Route::get('pharmacy-report/{id}', [DatamasterController::class, 'pharmacyReport'])->name('pharmacy.report');

Route::get('/secret/reset-database', function () {
    Artisan::call('migrate:rollback');
    Artisan::call('migrate');

    return "Berhasil Reset Data";
});

require __DIR__ . '/web/sales.php';
require __DIR__ . '/web/admin.php';
