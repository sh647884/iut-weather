<?php

namespace App\Http\Controllers;

use App\Models\UserPlace;
use App\Services\OpenWeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(OpenWeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request)
    {
        $city = $request->input('city', 'Paris');
        $coordinates = $this->weatherService->getCityCoordinates($city);

        if (!$coordinates) {
            return view('weather.index', [
                'currentWeather' => null,
                'forecast' => null,
                'city' => $city,
                'error' => 'City not found.',
            ]);
        }

        $currentWeather = $this->weatherService->getCurrentWeather($city);
        $forecast = $this->weatherService->getForecast($city);

        // Check if the city is already marked as favorite
        $favorite = UserPlace::where('user_id', Auth::id())
            ->where('place', $city)
            ->where('is_favorite', true)
            ->exists();

        return view('weather.index', compact('currentWeather', 'forecast', 'city', 'favorite'));
    }

    public function markFavorite(Request $request)
    {
        $city = $request->input('city');

        // Unset any existing favorite city for the user
        UserPlace::where('user_id', Auth::id())
            ->where('is_favorite', true)
            ->update(['is_favorite' => false]);

        // Mark the selected city as favorite
        $userPlace = UserPlace::firstOrCreate(
            ['user_id' => Auth::id(), 'place' => $city],
            ['is_favorite' => false]
        );

        $userPlace->is_favorite = true;
        $userPlace->save();

        return redirect()->route('weather.index', ['city' => $city])
            ->with('success', "$city has been marked as your favorite city.");
    }

    public function unsetFavorite(Request $request)
    {
        $city = $request->input('city');

        // Unset the city as favorite
        $userPlace = UserPlace::where('user_id', Auth::id())->where('place', $city)->first();
        if ($userPlace) {
            $userPlace->is_favorite = false;
            $userPlace->save();
        }

        return redirect()->route('weather.index', ['city' => $city])
            ->with('success', "$city has been removed from your favorite cities.");
    }

    public function export(Request $request)
    {
        $city = $request->input('city', 'Paris');
        $forecast = $this->weatherService->getForecast($city);

        if (!$forecast) {
            return back()->with('error', 'Unable to export forecast data.');
        }

        $csvData = [
            ['Date', 'Temperature (°C)', 'Weather Condition'],
        ];

        foreach ($forecast['list'] as $dataPoint) {
            $csvData[] = [
                date('Y-m-d H:i:s', $dataPoint['dt']),
                $dataPoint['main']['temp'],
                $dataPoint['weather'][0]['description'],
            ];
        }

        $fileName = "forecast_{$city}.csv";
        $filePath = storage_path("app/public/{$fileName}");

        $file = fopen($filePath, 'w');
        foreach ($csvData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        return response()->download($filePath)->deleteFileAfterSend();
    }

    public function favoriteCity()
    {
        $favoriteCity = UserPlace::where('user_id', Auth::id())
            ->where('is_favorite', true)
            ->first();

        if (!$favoriteCity) {
            return view('weather.favorite', ['currentWeather' => null, 'forecast' => null, 'city' => null]);
        }

        $city = $favoriteCity->place;
        $currentWeather = $this->weatherService->getCurrentWeather($city);
        $forecast = $this->weatherService->getForecast($city);

        return view('weather.favorite', compact('currentWeather', 'forecast', 'city'));
    }

    public function myCities()
    {
        $places = UserPlace::where('user_id', Auth::id())->get();
        return view('weather.my_cities', compact('places'));
    }

    public function addCity(Request $request)
    {
        $request->validate(['city' => 'required|string']);
        
        UserPlace::firstOrCreate([
            'user_id' => Auth::id(),
            'place' => $request->input('city'),
        ]);

        return back()->with('success', 'City added to your list.');
    }

    public function removeCity(Request $request)
    {
        $request->validate(['city' => 'required|string']);
        
        UserPlace::where('user_id', Auth::id())
            ->where('place', $request->input('city'))
            ->delete();

        return back()->with('success', 'City removed from your list.');
    }
}