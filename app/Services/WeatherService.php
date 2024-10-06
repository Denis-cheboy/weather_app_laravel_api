<?php

namespace App\Services;

use App\Interfaces\WeatherServiceInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WeatherService implements WeatherServiceInterface
{
    protected $httpClient;

    public function __construct(Client $client)
    {
        $this->httpClient = $client;
    }

    public function getWeatherForecast(string $city): array
    {
        // First, get coordinates for the city
        $coordinates = $this->getCityCoordinates($city);
        
        if (!$coordinates) {
            return ['error' => 'City not found'];
        }

        // Then, get the 7-day forecast using the coordinates
        $forecast = $this->getForecastByCoordinates($coordinates['lat'], $coordinates['lon']);
        return [
            'current' => $coordinates,
            'forecast' => $forecast
        ];
    }

     protected function getCityCoordinates(string $city): ?array
    {
        $response = $this->httpClient->get(config('weather.base_url'), [
            'query' => [
                'q' => $city,
                'appid' => config('weather.api_key'),
                'units' => 'metric',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (!isset($data['coord'])) {
            return null;
        }

        return [
            'lat' => $data['coord']['lat'],
            'lon' => $data['coord']['lon'],
            'current_weather' => $data
        ];
    }

      protected function getForecastByCoordinates(float $lat, float $lon): array
    {
        $response = $this->httpClient->get(config('weather.forecast_url'), [
            'query' => [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => config('weather.api_key'),
                'units' => 'metric',
                'exclude' => 'minutely,hourly' // Exclude unnecessary data
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
