<?php

namespace App\Service;

class CompareService
{
    public function calculateOffset(int|float $averageValue, int $amount)
    {
        $averageValue = $averageValue - $amount; // Offset of the average

        // If value is below 0 (negative number)
        if ($averageValue < 0) {
            $averageValue = $averageValue * -1;
        }

        return $averageValue;
    }

    public function assignPoints(int|float $city1offset, int|float $city2offset, int $points)
    {
        if ($city1offset < $city2offset) {
            $compareData['city1score'] += $points;
        } elseif ($city2offset < $city1offset) {
            $compareData['city2score'] += $points;
        }
    }
}