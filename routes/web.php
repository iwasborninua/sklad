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

Route::get('/dashboard', [\App\Http\Controllers\dashboard\DashboardController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/settings', [\App\Http\Controllers\dashboard\SettingsController::class, 'index'])->name('dashboard.settings');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::name('partial.')->prefix('partials')->group(function () {
    Route::name('settings.')->prefix('settings')->group(function () {
        Route::get('categories', function () {
            return view('partials.settings.categories', [
                'categories' => \App\Models\Sklad\CategorysSettings::getCategorysSettingsList(),
            ]);
        })->name('categories');

        Route::get('manufacturers', function () {
            return view('partials.settings.manufacturers', [
                'manufacturers' => \App\Models\Sklad\ManufacturersSettings::getManufacturersSettingsList(),
            ]);
        })->name('manufacturers');
    });
});
