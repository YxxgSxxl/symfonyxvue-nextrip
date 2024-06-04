<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/{city1}/{city2}', name: 'app_api')]
    public function compare(string $city1, string $city2): JsonResponse
    {
        // FETCH OpenWeather map API here
        $url_base = "https://api.openweathermap.org/data/2.5/";
        $api_key = "95542917d76459372397547a96610cd8";

        // if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        //     http_response_code(200);
        //     exit;
        // }

        // $query1 = new Response();
        $query1 = file_get_contents($url_base . "weather?q=" . $city1 . "&units=metric&appid=" . $api_key, true);
        // $query1->headers->set('Access-Control-Allow-Origin', '*');
        $query2 = file_get_contents($url_base . "weather?q=" . $city2 . "&units=metric&appid=" . $api_key, true);
        // $query2->headers->set('Access-Control-Allow-Origin', '*');
        // dd($query1, $query2);
        $cities = [json_decode($query1), json_decode($query2)];

        // dd($cities);

        // foreach ($query1 as $key => $value) {
        //     $tot = $key;
        // }

        // dd($cities);

        // $response = new Response();
        // $response->headers->set('Access-Control-Allow-Origin', '*');
        return $this->json([$cities]);
        // 'city1' => json_decode($query1),
        // 'city2' => json_decode($query2),

        // 'winner' => 'The winner is ' . $city2 . ' !',
        // 'path' => 'src/Controller/ApiController.php',
    }
}
