<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/{city1}/{city2}', name: 'app_api')]
    public function index(string $city1, string $city2): JsonResponse
    {

        // code

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $this->json([
            'winner' => 'The winner is PutACityHere !',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }
}
