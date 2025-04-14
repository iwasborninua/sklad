<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $categories = App\Models\Category::getActiveCategories();
    $manufacturers = App\Models\Manufacturer::getActiveManufacturers();
    $products = App\Models\Product::getActiveProducts();
    

    return view('dashboard', [
        'categories' => $categories,
        'manufacturers' => $manufacturers,
        'products' => $products,
    ]);
})->name('dashboard');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
