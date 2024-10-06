<?php

namespace Tests\Feature;

use Tests\TestCase;

class WeatherTest extends TestCase
{
    public function test_get_weather()
    {
        $response = $this->get('/api/weather?latitude=35.6895&longitude=139.6917'); // Example coordinates for Tokyo

        $response->assertStatus(200);
        $this->assertArrayHasKey('weather', $response->json());
    }

    public function test_invalid_coordinates()
    {
        $response = $this->get('/api/weather?latitude=invalid&longitude=invalid');

        $response->assertStatus(422); // Unprocessable entity
    }
}
