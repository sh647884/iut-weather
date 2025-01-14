<?php

namespace App\Providers;

use App\Models\UserPlace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Partager la variable favoriteCityName avec la vue `navigation.blade.php`
        View::composer('layouts.navigation', function ($view) {
            $favoriteCity = Auth::check()
                ? UserPlace::where('user_id', Auth::id())->where('is_favorite', true)->first()
                : null;

            $favoriteCityName = $favoriteCity ? $favoriteCity->place : null;

            $view->with('favoriteCityName', $favoriteCityName);
        });
    }
}
