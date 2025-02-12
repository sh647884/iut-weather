@component('mail::message')
# 🌦 Votre Bulletin Météo Quotidien

Voici les prévisions météo pour vos villes suivies.

@foreach ($forecasts as $city => $data)

## 📍 {{ $city }}

| Date & Heure | Température (°C) | Condition |
|-------------|-----------------|------------|
@foreach ($data as $forecast)
| {{ $forecast['date'] }} | {{ $forecast['temperature'] }}°C | {{ ucfirst($forecast['description']) }} |
@endforeach

@endforeach

📎 **Le fichier CSV contenant les détails est attaché à ce mail.**

Merci,  
{{ config('app.name') }}
@endcomponent