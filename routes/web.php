<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\dashboard\SettingsController;

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
    return redirect('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\dashboard\DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/settings', [\App\Http\Controllers\dashboard\SettingsController::class, 'index'])->name('dashboard.settings');
    Route::get('/dashboard/monitoring', [\App\Http\Controllers\dashboard\MonitoringController::class, 'index'])->name('dashboard.monitoring');
    Route::get('/dashboard/check', [\App\Http\Controllers\dashboard\MonitoringController::class, 'check'])->name('dashboard.check');
    Route::get('/dashboard/statistic', [\App\Http\Controllers\dashboard\StatisticsController::class, 'index'])->name('dashboard.statistic');

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

            Route::get('options', [SettingsController::class, 'options'])->name('options');
        });
    });
});

