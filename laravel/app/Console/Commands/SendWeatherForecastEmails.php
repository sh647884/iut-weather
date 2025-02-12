<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\OpenWeatherService;
use App\Mail\WeatherForecastMail;
use Illuminate\Support\Facades\Mail;

class SendWeatherForecastEmails extends Command
{
    protected $signature = 'weather:send-forecasts';
    protected $description = 'Send daily weather forecasts to users via email.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting weather forecast email dispatch...');

        $weatherService = new OpenWeatherService();
        $users = User::whereHas('places', function ($query) {
            $query->where('send_forecast', true);
        })->get();

        foreach ($users as $user) {
            $this->info("Processing user: {$user->email}");

            $forecasts = [];

            foreach ($user->places->where('send_forecast', true) as $place) {
                $weather = $weatherService->getForecast($place->place);

                if ($weather && isset($weather['list'])) {
                    $forecasts[$place->place] = [];

                    foreach ($weather['list'] as $entry) {
                        $forecasts[$place->place][] = [
                            'date' => date('Y-m-d H:i:s', $entry['dt']),
                            'temperature' => $entry['main']['temp'],
                            'description' => $entry['weather'][0]['description'],
                        ];
                    }
                }                
            }

            if (!empty($forecasts)) {
                Mail::to($user->email)->send(new WeatherForecastMail($forecasts));
                $this->info("Email sent to {$user->email}");
            } else {
                $this->warn("No weather data available for {$user->email}");
            }
        }

        $this->info('Weather forecast email dispatch completed.');
    }
}