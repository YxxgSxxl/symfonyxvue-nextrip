<?php

namespace App\Service;

class UtilityService
{
    public function getWeatherUrl(string $queryType, string $cityname, int|string $apiKey, int|string $apiBaseUrl): string
    {
        $getWeatherParams = [
            'q' => $cityname,
            'units' => 'metric',
            'appid' => $apiKey
        ];
        return $apiBaseUrl . $queryType . '?' . http_build_query($getWeatherParams);
    }

    public function getWeatherFullUrl(string $queryType, array &$compareDataElement, $apiKey, $apiBaseUrl): string
    {
        $getWeatherParams = [
            'lat' => $compareDataElement['coord']['lat'],
            'lon' => $compareDataElement['coord']['lon'],
            'units' => 'metric',
            'appid' => $apiKey
        ];
        return $apiBaseUrl . $queryType . '?' . http_build_query($getWeatherParams);
    }

    public function constructCityTodayResponseArray(string $name, array &$compareData, array &$responseArray)
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
}