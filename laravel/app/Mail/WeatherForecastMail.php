<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeatherForecastMail extends Mailable
{
    use Queueable, SerializesModels;

    public $forecasts;

    /**
     * Create a new message instance.
     */
    public function __construct($forecasts)
    {
        $this->forecasts = $forecasts;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Daily Weather Forecast')
                    ->markdown('emails.weather_forecast')
                    ->attachData($this->generateCsv(), 'weather_forecast.csv', [
                        'mime' => 'text/csv',
                    ]);
    }

    /**
     * Generate CSV content.
     */
    private function generateCsv()
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Ville', 'Date', 'Température (°C)', 'Condition']);

        foreach ($this->forecasts as $city => $data) {
            foreach ($data as $forecast) {
                fputcsv($handle, [$city, $forecast['date'], $forecast['temperature'], $forecast['description']]);
            }
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }
}