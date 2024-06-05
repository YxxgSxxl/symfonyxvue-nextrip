<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApiController extends AbstractController
{
    #[Route('/api/{city1}/{city2}', name: 'app_api')]
    public function compare(string $city1, string $city2, ParameterBagInterface $parameterBag): JsonResponse
    {
        $responseArray = [];
        // FETCH OpenWeather map API here
        $url_base = "https://api.openweathermap.org/data/2.5/";
        $api_key = $parameterBag->get("API_KEY_SECRET");

        // if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        //     http_response_code(200);
        //     exit;
        // }

        // OpenWeatherMap API Calls
        $query1 = file_get_contents($url_base . "weather?q=" . $city1 . "&units=metric&appid=" . $api_key, true);
        $query2 = file_get_contents($url_base . "weather?q=" . $city2 . "&units=metric&appid=" . $api_key, true);

        $cities = [json_decode($query1), json_decode($query2)];

        $query3 = file_get_contents($url_base . "forecast?lat=" . $cities[0]->coord->lat . "&lon=" . $cities[0]->coord->lon . "&units=metric&appid=" . $api_key, true);
        $query4 = file_get_contents($url_base . "forecast?lat=" . $cities[1]->coord->lat . "&lon=" . $cities[1]->coord->lon . "&units=metric&appid=" . $api_key, true);

        $citiesAll = [json_decode($query3), json_decode($query4)];

        // dd($cities, $cities[0]->base, $cities[1]->main->temp, $citiesAll[0]->list);

        $responseArray['cities'] = $cities; // Cities
        $responseArray['citiesAll'] = $citiesAll; // Cities full informations (5 days long)
        // $responseArray['api_key'] = $parameterBag->get("API_KEY_SECRET");

        // dd();

        // Algorythm
        $points = [];
        $points['city1'] = 0;
        $points['city2'] = 0;

        if ($responseArray['cities'][0]->name == $responseArray['citiesAll'][0]->city->name) {
            $points['city1'] = 10;
            dd('success!', $points);
        } else {
            dd('nooo');
        }

        $responseArray['winner'] = ""; // Winners

        return $this->json($responseArray);
    }
}
