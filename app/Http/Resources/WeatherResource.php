<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'temperature' => $this['main']['temp'],
            'feels_like' => $this['main']['feels_like'],
            'humidity' => $this['main']['humidity'],
            'wind_speed' => $this['wind']['speed'],
            'description' => $this['weather'][0]['description'],
            'icon' => $this['weather'][0]['icon'],
            'location' => [
                'lat' => $this['coord']['lat'],
                'lon' => $this['coord']['lon'],
                'city' => $this['name'],
                'country' => $this['sys']['country'],
            ],
        ];
    }
}