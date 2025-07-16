<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ProductController::class, 'list']);
Route::put('/product/{identifier}/{count}', [ProductController::class, 'updateQuantity']);
Route::get('/products-with-options', [ProductController::class, 'productsWithOptions']);
Route::put('/products-with-options/{productOptionValueId}/{count}', [ProductController::class, 'updateOptionQuantity']);

Route::name('settings.')->prefix('settings')->group(function () {
    Route::name('sync.')->prefix('sync')->group(function () {
        Route::get('manufacturers', [\App\Http\Controllers\Api\SettingsController::class, 'syncManufacturers'])->name('manufacturers');
        Route::get('categories', [\App\Http\Controllers\Api\SettingsController::class, 'syncCategories'])->name('categories');
    });

    Route::name('update.')->prefix('update')->group(function () {
        Route::post('manufacturers', [\App\Http\Controllers\Api\SettingsController::class, 'updateManufacturers'])->name('manufacturers');
        Route::post('categories', [\App\Http\Controllers\Api\SettingsController::class, 'updateCategories'])->name('categories');
    });
});
