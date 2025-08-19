<?php

use App\Http\Controllers\Api\ProductController;
use App\Models\Product;
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
Route::get('/get-table-columns/{manufacturer_id?}/{category_id?}', [Product::class, 'getProductOptionsName']);
Route::put('/option/{identifier}/{optionName}/{value}', [ProductController::class, 'updateOption']);

Route::put('/product/{identifier}/{count}', [ProductController::class, 'updateQuantity']);
Route::get('/products-with-options', [ProductController::class, 'productsWithOptions']);
Route::put('/products-with-options/{productOptionValueId}/{count}', [ProductController::class, 'updateOptionQuantity']);

Route::name('settings.')->prefix('settings')->group(function () {
    Route::name('sync.')->prefix('sync')->group(function () {
        Route::post('manufacturers', [\App\Http\Controllers\Api\SettingsController::class, 'syncManufacturers'])->name('manufacturers');
        Route::post('categories', [\App\Http\Controllers\Api\SettingsController::class, 'syncCategories'])->name('categories');
    });

    Route::name('update.')->prefix('update')->group(function () {
        Route::post('manufacturers', [\App\Http\Controllers\Api\SettingsController::class, 'updateManufacturers'])->name('manufacturers');
        Route::post('categories', [\App\Http\Controllers\Api\SettingsController::class, 'updateCategories'])->name('categories');
        Route::post('options', [\App\Http\Controllers\Api\SettingsController::class, 'updateOptions'])->name('options');
    });
});
