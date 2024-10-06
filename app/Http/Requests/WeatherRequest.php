<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeatherRequest extends FormRequest
{
    public function rules()
    {
        return [
            'q' => 'required|string|max:255', // City name validation
        ];
    }

    public function messages()
    {
        return [
            'q.required' => 'City name is required',
            'q.string' => 'City name must be a string',
            'q.max' => 'City name must not exceed 255 characters',
        ];
    }
}
