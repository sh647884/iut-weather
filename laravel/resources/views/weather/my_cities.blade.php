<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Cities') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($places->isEmpty())
                        <p class="text-red-500">You don't have any cities in your list yet.</p>
                    @else
                        <h3 class="text-lg font-semibold mb-4">Your Cities</h3>
                        <div class="flex flex-wrap">
                            @foreach ($places as $place)
                                <div class="relative city-container">
                                    <form method="GET" action="{{ route('weather.index') }}" class="city-button-form">
                                        <input type="hidden" name="city" value="{{ $place->place }}">
                                        <button type="submit" class="city-button">{{ $place->place }}</button>
                                    </form>
                                    <label class="star-container">
                                        <form method="POST" action="{{ $place->is_favorite ? route('weather.unsetFavorite') : route('weather.favorite') }}">
                                            @csrf
                                            <input type="hidden" name="city" value="{{ $place->place }}">
                                            <input 
                                                type="checkbox" 
                                                name="favorite" 
                                                {{ $place->is_favorite ? 'checked' : '' }} 
                                                data-city="{{ $place->place }}" 
                                                onchange="this.form.submit()"
                                            >
                                            <svg height="24px" width="24px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.362,9.158c0,0-3.16,0.35-5.268,0.584c-0.19,0.023-0.358,0.15-0.421,0.343s0,0.394,0.14,0.521    c1.566,1.429,3.919,3.569,3.919,3.569c-0.002,0-0.646,3.113-1.074,5.19c-0.036,0.188,0.032,0.387,0.196,0.506    c0.163,0.119,0.373,0.121,0.538,0.028c1.844-1.048,4.606-2.624,4.606-2.624s2.763,1.576,4.604,2.625    c0.168,0.092,0.378,0.09,0.541-0.029c0.164-0.119,0.232-0.318,0.195-0.505c-0.428-2.078-1.071-5.191-1.071-5.191    s2.353-2.14,3.919-3.566c0.14-0.131,0.202-0.332,0.14-0.524s-0.23-0.319-0.42-0.341c-2.108-0.236-5.269-0.586-5.269-0.586    s-1.31-2.898-2.183-4.83c-0.082-0.173-0.254-0.294-0.456-0.294s-0.375,0.122-0.453,0.294C10.671,6.26,9.362,9.158,9.362,9.158z"></path>
                                            </svg>
                                        </form>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Weather Data -->
                    @if ($currentWeather ?? false)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold">Weather in {{ $city }}</h3>
                            <p>Temperature: {{ $currentWeather['main']['temp'] }} °C</p>
                            <p>Condition: {{ $currentWeather['weather'][0]['description'] }}</p>
                            <p>Humidity: {{ $currentWeather['main']['humidity'] }}%</p>
                            <p>Wind Speed: {{ $currentWeather['wind']['speed'] }} m/s</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>