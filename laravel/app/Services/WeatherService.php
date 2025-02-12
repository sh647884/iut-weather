<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public function getCurrentWeather($place)
    {
        $apiKey = config('services.openweather.key');
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
            'q' => $place,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'fr'
        ]);

        return $response->json();
    }

    public function getForecast($place)
    {
        $apiKey = config('services.openweather.key');
        $response = Http::get("https://api.openweathermap.org/data/2.5/forecast", [
            'q' => $place,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'fr'
        ]);

        return $response->json();
    }
}