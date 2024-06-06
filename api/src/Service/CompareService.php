<?php

namespace App\Service;

class CompareService
{
    public function calculateTotal($totalValue, $responseArrayLoc, $listSize)
    {
        $totalValue += $responseArrayLoc * $listSize;
        return $totalValue;
    }
}