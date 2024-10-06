<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getWeather(Request $request): JsonResponse
    {
        // Validate the incoming request for the 'q' parameter (city name)
        $validated = $request->validate([
            'q' => 'required|string|max:255',
        ]);

        // Log the incoming request data
        info('Received request for weather data', $validated);

        // Extract city name from validated data
        $city = $validated['q'];

        try {
            // Log the city name before fetching data
            info("Fetching weather data for city: $city");

            // Fetch weather data using the city name
            $weatherData = $this->weatherService->getWeatherForecast($city);

            // Return the weather data in the response
            return response()->json($weatherData);
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error fetching weather data', [
                'message' => $e->getMessage(),
                'city' => $city,
            ]);

            return response()->json([
                'error' => 'Unable to fetch weather data. Please try again later.',
                'message' => $e->getMessage(), // Optional
                'code' => 'WEATHER_FETCH_ERROR',
            ], 500);
        }
    }
}
