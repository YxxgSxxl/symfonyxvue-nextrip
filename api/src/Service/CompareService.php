<?php

namespace App\Service;

class CompareService
{
    public function calculateTotal(int|float $totalValue, int|float $responseArrayLoc)
    {
        $totalValue += $responseArrayLoc;
        return $totalValue;
    }

    public function calculateOffset(int|float $averageValue, int $amount)
    {
        $averageValue = $averageValue - $amount; // Offset of the average

        // If value is below 0 (negative number)
        if ($averageValue < 0) {
            $averageValue = $averageValue * -1;
        }

        return $averageValue;
    }
}