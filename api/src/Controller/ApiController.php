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

        $citiesAll = [json_decode($query3), json_decode($query4)]; // Decode JSON queries

        $responseArray['cities'] = $cities; // Cities
        $responseArray['citiesAll'] = $citiesAll; // Cities full informations (5 days long)
        // $responseArray['api_key'] = $parameterBag->get("API_KEY_SECRET");

        // dd();

        // Algorythm
        $compareData = []; // This array contains the average of their weather values and the score
        $compareData['city1'] = ['temp' => null, 'humidity' => null, 'cloud' => null, 'score' => 0];
        $compareData['city2'] = ['temp' => null, 'humidity' => null, 'cloud' => null, 'score' => 0];

        // if the city name of the first queries are the same
        if ($responseArray['cities'][0]->name == $responseArray['citiesAll'][0]->city->name) {
            $listSize = count($responseArray['citiesAll'][0]->list); // size of the array

            // For loop that goes all the way up the array
            for ($i = 0; $i < $listSize; $i++) {
                // First city total
                $total = 0;
                $total += $responseArray['citiesAll'][0]->list[$i]->main->temp * $listSize;
                $cit1moy = $total / $listSize;
                $cit1moy = $cit1moy - 27;

                // Second city total
                $total = 0;
                $total += $responseArray['citiesAll'][1]->list[$i]->main->temp * $listSize;
                $cit2moy = $total / $listSize;
                $cit2moy = $cit2moy - 27;

                // if cit1moy value is below 0, make it positive
                if ($cit1moy < 0) {
                    $cit1moy = $cit1moy * -1;
                }

                // if cit2moy value is below 0, make it positive
                if ($cit2moy < 0) {
                    $cit2moy = $cit2moy * -1;
                }

                // inject average values of temp
                $compareData['city1']['temp'] = $cit1moy;
                $compareData['city2']['temp'] = $cit2moy;

                // if cit1moy value has the lowest difference
                if ($cit1moy < $cit2moy) {
                    $compareData['city1']['score'] = 20;
                }

                // if cit2moy value has the lowest difference
                if ($cit2moy < $cit1moy) {
                    $compareData['city2']['score'] = 20;
                }

                dd($compareData);


                // Humidity check

                // Clourds rate check
            }
            // dd('');
            // dd($listSize);
        } else {
            null;
        }

        $responseArray['winner'] = ""; // Winners

        return $this->json($responseArray);
    }
}
