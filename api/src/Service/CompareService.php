<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class CompareService
{
    const TRESH_TEMP = 27;
    const TEMP_POINTS = 20;
    const TRESH_HUM = 60;
    const HUM_POINTS = 15;
    const TRESH_CLOUDS = 15;
    const CLOUDS_POINTS = 10;

/*    public function ifApiCallIsDeprecated(array &$weatherCall1, array &$weatherCall2)
    {
        if (
            !isset($getWeather1['weather']) ||
            !isset($getWeather1['weather'][0]['icon']) ||
            !isset($getWeather1['name']) ||
            !isset($getWeather1['sys']['country']) ||
            !isset($getWeather1['main']['temp']) ||
            !isset($getWeather1['main']['humidity']) ||
            !isset($getWeather1['clouds']) ||
            !isset($getWeather1['wind']['speed']) ||

            !isset($getWeather2['weather']) ||
            !isset($getWeather2['weather'][0]['icon']) ||
            !isset($getWeather2['name']) ||
            !isset($getWeather2['sys']['country']) ||
            !isset($getWeather2['main']['temp']) ||
            !isset($getWeather2['main']['humidity']) ||
            !isset($getWeather2['clouds']) ||
            !isset($getWeather2['wind']['speed'])
        ) {
            return new JsonResponse(['error' => ['message' => 'Une erreur est survenue', 'name' => 'Résultat requête API incorect']], 400);
        }

        return [$weatherCall1, $weatherCall2];
    }*/
    
    /**
     * This function calculates the offset of the average
     * and if the value is below zero, it makes it positive.
     * 
     * returns a int/float value.
     */
    public function calculateOffset(int|float $averageValue, int $tresh): int
    {
        $averageValue = abs($averageValue - $tresh); // Offset of the average
        return $averageValue;
    }

    /**
     * This function compare wich offset is the lowest
     * and assigns points to the right array key.
     * 
     * returns a int value into an array.
     */
    public function assignPoints(int|float $cityOffset1, int|float $cityOffset2, array &$compareData, int $points): void
    {
        if ($cityOffset1 < $cityOffset2) {
            $compareData['city1score'] += $points;
        } elseif ($cityOffset1 > $cityOffset2) {
            $compareData['city2score'] += $points;
        } elseif ($cityOffset1 = $cityOffset2) {
            $compareData['city1score'] += $points;
            $compareData['city2score'] += $points;
        }
    }

    /**
     * This function compare and determine the winner and the loser
     * by comparing the score of the two cities.
     * 
     * returns a winner with his values and a loser in an array.
     */
    public function determineWinLose(array &$compareData, array &$responseArray): void
    {
        if ($compareData['city1score'] > $compareData['city2score']) {
            $responseArray['citywinner'] = ['name' => $compareData['city1']['name'], 'country' => $compareData['city1']['sys']['country'], 'score' => $compareData['city1score'], 'tempavg' => $compareData['average']['temp1'], 'humavg' => $compareData['average']['hum1'], 'cloudsavg' => $compareData['average']['clouds1']];
            $responseArray['cityloser'] = ['name' => $compareData['city2']['name'], 'country' => $compareData['city2']['sys']['country'], 'score' => $compareData['city2score'], 'tempavg' => $compareData['average']['temp2'], 'humavg' => $compareData['average']['hum2'], 'cloudsavg' => $compareData['average']['clouds2']];
        } elseif ($compareData['city1score'] < $compareData['city2score']) {
            $responseArray['citywinner'] = ['name' => $compareData['city2']['name'], 'country' => $compareData['city2']['sys']['country'], 'score' => $compareData['city2score'], 'tempavg' => $compareData['average']['temp2'], 'humavg' => $compareData['average']['hum2'], 'cloudsavg' => $compareData['average']['clouds2']];
            $responseArray['cityloser'] = ['name' => $compareData['city1']['name'], 'country' => $compareData['city1']['sys']['country'], 'score' => $compareData['city1score'], 'tempavg' => $compareData['average']['temp1'], 'humavg' => $compareData['average']['hum1'], 'cloudsavg' => $compareData['average']['clouds1']];
        } elseif ($compareData['city1score'] = $compareData['city2score']) {
            // TODO Que fait-on si on a égalité ?

        }
    }
}