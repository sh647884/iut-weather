<?php

use App\Http\Controllers\Api\WeatherController;
use App\Http\Controllers\Api\UserPlaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route de test de l'authentification via Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes météo
Route::get('/v1/weather', [WeatherController::class, 'current']);
Route::get('/v1/weather/forecast', [WeatherController::class, 'forecast']);

// Routes de gestion des villes de l'utilisateur
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/v1/users/places', [UserPlaceController::class, 'index']);
    Route::post('/v1/users/places', [UserPlaceController::class, 'store']);
    Route::patch('/v1/users/places/{place}/send-forecast', [UserPlaceController::class, 'toggleForecast']);
    Route::patch('/v1/users/places/{place}/favorite', [UserPlaceController::class, 'toggleFavorite']);
    Route::delete('/v1/users/places/{place}', [UserPlaceController::class, 'destroy']);
});