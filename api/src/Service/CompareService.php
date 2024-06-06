<?php

namespace App\Service;

class CompareService
{
    public function calculateTotal($totalValue, $responseArrayLoc, $listSize)
    {
        $totalValue += $responseArrayLoc * $listSize;
        return $totalValue;
    }

    public function calculateOffset($averageValue, $totalValue, $amount)
    {
        $averageValue = $averageValue - $amount; // Offset of the average
        return $averageValue;
    }
}