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
        $compareData = [];
        $compareData['city1'] = ['temp' => null, 'humidity' => null, 'cloud' => null, 'score' => 0];
        $compareData['city2'] = ['temp' => null, 'humidity' => null, 'cloud' => null, 'score' => 0];

        // if the city name of the first queries are the same
        if ($responseArray['cities'][0]->name == $responseArray['citiesAll'][0]->city->name) {
            $listSize = count($responseArray['citiesAll'][0]->list); // size of the array

            // For loop that goes all the way up the array
            for ($i = 0; $i < $listSize; $i++) {
                // Temperature check
                // if ($responseArray['citiesAll'][0]->list[$i]->main->temp == $responseArray['citiesAll'][1]->list[$i]->main->temp) {
                //     dd('SAMEEEE');
                // } elseif ($responseArray['citiesAll'][0]->list[$i]->main->temp > 27) {
                //     print_r("Array[" . $i . "]--> " . $responseArray['citiesAll'][0]->list[$i]->main->temp . "°C" . ' est plus <span style="color: red">chaud</span> que 27°C de ' . $responseArray['citiesAll'][0]->list[$i]->main->temp - 27 . "°C.<br>");
                //     // print_r(array_diff($responseArray['citiesAll'][0]->list[$i]->main->temp, 27) . " " . $i . "<br>");
                // } elseif ($responseArray['citiesAll'][0]->list[$i]->main->temp < 27) {
                //     print_r("Array[" . $i . "]--> " . $responseArray['citiesAll'][0]->list[$i]->main->temp . "°C" . ' est plus <span style="color: lightblue">froid</span> que 27°C de ' . 27 - $responseArray['citiesAll'][0]->list[$i]->main->temp . "°C.<br>");
                //     // print_r(array_diff($responseArray['citiesAll'][0]->list[$i]->main->temp, 27) . " " . $i . "<br>");
                // } else {
                //     // dd('DIFFERENT');
                //     // dd($responseArray['citiesAll'][0]->list[$i]->main->temp);
                // }

                $total = 0;
                $total += $responseArray['citiesAll'][0]->list[$i]->main->temp * $listSize;
                $moyenne = $total / $listSize;
                dd($total, $moyenne);
                // Humidity check
                // Clourds rate check
            }
            dd('');

            // dd($listSize);

            // for ($i = 0; $i < $listSize; $i++) {
            //     print_r($responseArray['citiesAll'][0]->list[$i]->main->temp);
            // }
        } else {
            null;
        }

        $responseArray['winner'] = ""; // Winners

        return $this->json($responseArray);
    }
}
