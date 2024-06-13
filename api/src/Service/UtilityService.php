<?php

namespace App\Service;

class UtilityService
{
    public function constructResponseArray(string $name, array &$compareData, array &$responseArray)
    {
        return $responseArray[$name] = [
            'icon' => $compareData['weather'][0]['icon'],
            'name' => $compareData['name'],
            'country' => $compareData['sys']['country'],
            'temp' => $compareData['main']['temp'],
            'humidity' => $compareData['main']['humidity'],
            'clouds' => $compareData['clouds']['all'],
            'wind' => $compareData['wind']['speed']
        ];
    }

    public function getWeatherUrl(string $cityname, $apiBaseUrl, $apiKey): string
    {
        $getWeatherParams = [
            'q' => $cityname,
            'units' => 'metric',
            'appid' => $apiKey
        ];
        return $apiBaseUrl . '?' . http_build_query($getWeatherParams);
    }

    public function getWeatherFullUrl(array $compareDataElement): string
    {
        $getWeatherParams = [
            'lat' => $compareDataElement['coord']['lat'],
            'lon' => $compareDataElement['coord']['lon'],
            'units' => 'metric',
            'appid' => $apiKey
        ];
        return $apiBaseUrl . '?' . http_build_query($getWeatherParams);
    }
}