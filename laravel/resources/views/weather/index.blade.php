<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Weather Information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('weather.index') }}">
                        <label for="city" class="block text-sm font-medium text-gray-700">Enter City :</label>
                        <input type="text" name="city" id="city" class="custom-input" placeholder="Paris...">
                        <button type="submit" class="custom-button">Search</button>
                    </form>

                    @if ($currentWeather)
                        <h3 class="mt-4 text-lg font-semibold">Weather in {{ $city }}</h3>
                        <p>Temperature: {{ $currentWeather['main']['temp'] }} °C</p>
                        <p>Condition: {{ $currentWeather['weather'][0]['description'] }}</p>
                        <p>Humidity: {{ $currentWeather['main']['humidity'] }}%</p>
                        <p>Wind Speed: {{ $currentWeather['wind']['speed'] }} m/s</p>

                        <!-- Favorite Button -->
                        @if (!$favorite)
                            <form method="POST" action="{{ route('weather.favorite') }}" class="mt-4">
                                @csrf
                                <input type="hidden" name="city" value="{{ $city }}">
                                <button type="submit" class="custom-button">Mark as Favorite</button>
                            </form>
                        @else
                            <p class="mt-4 text-green-500">{{ $city }} is your favorite city.</p>
                            <form method="POST" action="{{ route('weather.unsetFavorite') }}" class="mt-4">
                                @csrf
                                <input type="hidden" name="city" value="{{ $city }}">
                                <button type="submit" class="custom-button">Unset Favorite</button>
                            </form>
                        @endif

                        <!-- Add/Remove from List -->
                        @php
                            $inList = \App\Models\UserPlace::where('user_id', Auth::id())->where('place', $city)->exists();
                        @endphp
                        @if (!$inList)
                            <form method="POST" action="{{ route('weather.addCity') }}" class="mt-4">
                                @csrf
                                <input type="hidden" name="city" value="{{ $city }}">
                                <button type="submit" class="custom-button">+ | Add to List</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('weather.removeCity') }}" class="mt-4">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="city" value="{{ $city }}">
                                <button type="submit" class="custom-button">- | Remove from List</button>
                            </form>
                        @endif

                        <form method="GET" action="{{ route('weather.export') }}" class="mt-4">
                            <input type="hidden" name="city" value="{{ $city }}">
                            <button type="submit" class="custom-button">Export Forecast as CSV</button>
                        </form>

                        @if ($forecast)
                            <h4 class="mt-6 text-lg font-semibold">Forecast for {{ $city }}</h4>
                            <ul>
                                @foreach ($forecast['list'] as $dataPoint)
                                    <li>
                                        {{ date('Y-m-d H:i:s', $dataPoint['dt']) }}: 
                                        {{ $dataPoint['main']['temp'] }} °C, 
                                        {{ $dataPoint['weather'][0]['description'] }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @else
                        <p class="mt-4 text-red-500">{{ $error ?? 'Unable to retrieve weather data. Please try again.' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>