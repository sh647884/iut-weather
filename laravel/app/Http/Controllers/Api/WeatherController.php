<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OpenWeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(OpenWeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function current(Request $request)
    {
        $request->validate([
            'place' => 'required|string',
        ]);

        $city = $request->input('place');
        $currentWeather = $this->weatherService->getCurrentWeather($city);

        if (!$currentWeather) {
            return response()->json(['error' => 'City not found'], 404);
        }

        return response()->json($currentWeather);
    }

    public function forecast(Request $request)
    {
        $request->validate([
            'place' => 'required|string',
        ]);

        $city = $request->input('place');
        $forecast = $this->weatherService->getForecast($city);

        if (!$forecast) {
            return response()->json(['error' => 'City not found'], 404);
        }

        return response()->json($forecast);
    }
}