<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // My Cities routes
    Route::get('/my-cities', [WeatherController::class, 'myCities'])->name('weather.myCities');
    Route::post('/weather/add-city', [WeatherController::class, 'addCity'])->name('weather.addCity');
    Route::delete('/weather/remove-city', [WeatherController::class, 'removeCity'])->name('weather.removeCity');
});

// Weather routes
Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/weather/export', [WeatherController::class, 'export'])->name('weather.export');
Route::post('/weather/favorite', [WeatherController::class, 'markFavorite'])->name('weather.favorite');
Route::post('/weather/unset-favorite', [WeatherController::class, 'unsetFavorite'])->name('weather.unsetFavorite');
Route::get('/weather/favorite', [WeatherController::class, 'favoriteCity'])->name('weather.favoriteCity');


require __DIR__.'/auth.php';