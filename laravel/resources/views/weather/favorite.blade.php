<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Favorite City Weather') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($city)
                        <h3 class="text-lg font-semibold">Weather in {{ $city }}</h3>
                        <p>Temperature: {{ $currentWeather['main']['temp'] }} °C</p>
                        <p>Condition: {{ $currentWeather['weather'][0]['description'] }}</p>
                        <p>Humidity: {{ $currentWeather['main']['humidity'] }}%</p>
                        <p>Wind Speed: {{ $currentWeather['wind']['speed'] }} m/s</p>
                    @else
                        <p class="text-red-500">You don't have a favorite city set yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>