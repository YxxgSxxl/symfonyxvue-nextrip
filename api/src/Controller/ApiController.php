<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

use App\Service\CompareService; // Custom service that help comparing datas

class ApiController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(string $message = null): JsonResponse
    {
        $message = "Welcome to the REST API of this project, to use the API, you can paste this after your DNS: /api/putACityHere1/putACityHere2. It will returns a lot of weather informations about the two cities provided, enjoy!";

        return $this->json($message);
    }

    #[Route('/api/{city1}/{city2}', name: 'app_api')]
    public function compare(string $city1, string $city2, ParameterBagInterface $parameterBag, CompareService $compare): JsonResponse
    {
        
    }
}
