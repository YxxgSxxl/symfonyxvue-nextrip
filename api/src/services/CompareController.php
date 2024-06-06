<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Compare extends AbstractController
{
    public function CalculateTotal($totalvalue, $responseArrayLoc, $listSize)
    {
        $totalvalue = 0;
        $totalvalue += $responseArrayLoc * $listSize;

        return $totalvalue;
    }
}