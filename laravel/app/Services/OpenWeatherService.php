<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenWeatherService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
    }

    public function getCurrentWeather(string $city)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
            'lang' => 'en',
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    public function getForecast(string $city)
    {
        $response = Http::get('https://api.openweathermap.org/data/2.5/forecast', [
            'q' => $city,
            'appid' => $this->apiKey,
            'units' => 'metric',
            'lang' => 'en',
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    public function getCityCoordinates(string $city)
    {
        $response = Http::get('https://api.openweathermap.org/geo/1.0/direct', [
            'q' => $city,
            'limit' => 1,
            'appid' => $this->apiKey,
        ]);

        if ($response->failed() || empty($response->json())) {
            return null;
        }

        return $response->json()[0];
    }
}