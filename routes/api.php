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
Route::get('/get-table-colums/{manufacturer_id?}', function ($manufacturerId = 37) {
    $products = Product::select(
            'oc_option_value_description.name as option_value_name',
        )
        ->where('manufacturer_id', $manufacturerId)
        ->where('oc_option_value_description.language_id', 1)
        ->leftJoin('oc_product_option', 'oc_product_option.product_id', '=', 'oc_product.product_id')
        ->leftJoin('oc_product_option_value', 'oc_product_option_value.product_option_id', '=', 'oc_product_option.product_option_id')
        ->leftJoin('oc_option_value_description', 'oc_option_value_description.option_value_id', '=', 'oc_product_option_value.option_value_id')
        ->distinct()

        ->get();

    $products->each(function ($product) {
        $product->option_value_name = (int)$product->option_value_name;
    });

    $products = $products->pluck('option_value_name')->unique()->sort()->values();

    return response()->json($products);
});
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
