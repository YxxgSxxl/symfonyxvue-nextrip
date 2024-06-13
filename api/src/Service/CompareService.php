<?php

namespace App\Service;

class CompareService
{
    const TRESH_TEMP = 27;
    const TEMP_POINTS = 20;
    const TRESH_HUM = 60;
    const HUM_POINTS = 15;
    const TRESH_CLOUDS = 15;
    const CLOUDS_POINTS = 10;

    // public function __construct(int $treshTemp = 27, int $treshHum = 60, int $treshClouds = 15)
    // {
    //     $this->treshTemp = $treshTemp; // Treshold of the wanted temp
    //     $this->treshHum = $treshHum; // Treshold of the wanted humidity
    //     $this->treshClouds = $treshClouds; // Treshold of the wanted clouds rate
    // }

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
    public function assignPoints(int|float $city1offset, int|float $city2offset, array &$compareData, int $points): void
    {
        // TODO camelCase partout
        if ($city1offset < $city2offset) {
            $compareData['city1score'] += $points;
        } elseif ($city2offset < $city1offset) {
            $compareData['city2score'] += $points;
        }
        //TODO : Qu'est ce qu'on fait si la différence est égale à 0 ?
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
        } elseif ($compareData['city2score'] > $compareData['city1score']) {
            $responseArray['citywinner'] = ['name' => $compareData['city2']['name'], 'country' => $compareData['city2']['sys']['country'], 'score' => $compareData['city2score'], 'tempavg' => $compareData['average']['temp2'], 'humavg' => $compareData['average']['hum2'], 'cloudsavg' => $compareData['average']['clouds2']];
            $responseArray['cityloser'] = ['name' => $compareData['city1']['name'], 'country' => $compareData['city1']['sys']['country'], 'score' => $compareData['city1score'], 'tempavg' => $compareData['average']['temp1'], 'humavg' => $compareData['average']['hum1'], 'cloudsavg' => $compareData['average']['clouds1']];
        }
        // TODO Que fait-on si on a égalité ?
    }
}