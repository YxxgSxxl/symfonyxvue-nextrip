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
        $compareData['city1'] = ['name' => null, 'temp' => null, 'humidity' => null, 'clouds' => null, 'score' => 0];
        $compareData['city2'] = ['name' => null, 'temp' => null, 'humidity' => null, 'clouds' => null, 'score' => 0];

        // if the city name of the first queries are the same
        if ($responseArray['cities'][0]->name == $responseArray['citiesAll'][0]->city->name) {
            $compareData['city1']['name'] = $responseArray['citiesAll'][0]->city->name; // add names on the compareData array
            $compareData['city2']['name'] = $responseArray['citiesAll'][1]->city->name; // add names on the compareData array

            $listSize = count($responseArray['citiesAll'][0]->list); // size of the array

            // For loop that goes all the way up the array
            for ($i = 0; $i < $listSize; $i++) {
                // COMPARING THE TEMPERATURE
                // First city temp total
                $total = 0;
                $total += $responseArray['citiesAll'][0]->list[$i]->main->temp * $listSize;
                $cit1tempmoy = $total / $listSize;
                $cit1tempmoy = $cit1tempmoy - 27;

                // Second city temp total
                $total = 0;
                $total += $responseArray['citiesAll'][1]->list[$i]->main->temp * $listSize;
                $cit2tempmoy = $total / $listSize;
                $cit2tempmoy = $cit2tempmoy - 27;

                // if cit1tempmoy value is below 0, make it positive
                if ($cit1tempmoy < 0) {
                    $cit1tempmoy = $cit1tempmoy * -1;
                }

                // if cit2tempmoy value is below 0, make it positive
                if ($cit2tempmoy < 0) {
                    $cit2tempmoy = $cit2tempmoy * -1;
                }

                // inject average values of temp
                $compareData['city1']['temp'] = $cit1tempmoy;
                $compareData['city2']['temp'] = $cit2tempmoy;

                // if cit1tempmoy value has the lowest difference
                if ($cit1tempmoy < $cit2tempmoy) {
                    $compareData['city1']['score'] = $compareData['city1']['score'] + 20;
                }

                // if cit2moy value has the lowest difference
                if ($cit2tempmoy < $cit1tempmoy) {
                    $compareData['city2']['score'] = $compareData['city2']['score'] + 20;
                }

                // COMPARING THE HUMIDITY
                // First city humidity total
                $total = 0;
                $total += $responseArray['citiesAll'][0]->list[$i]->main->humidity * $listSize;
                $cit1hummoy = $total / $listSize;
                $cit1hummoy = $cit1hummoy - 60;

                // First city humidity total
                $total = 0;
                $total += $responseArray['citiesAll'][1]->list[$i]->main->humidity * $listSize;
                $cit2hummoy = $total / $listSize;
                $cit2hummoy = $cit2hummoy - 60;

                // if cit1hummoy value is below 0, make it positive
                if ($cit1hummoy < 0) {
                    $cit1hummoy = $cit1hummoy * -1;
                }

                // if cit2hummoy value is below 0, make it positive
                if ($cit2hummoy < 0) {
                    $cit2hummoy = $cit2hummoy * -1;
                }

                // inject average values of hum
                $compareData['city1']['humidity'] = $cit1hummoy;
                $compareData['city2']['humidity'] = $cit2hummoy;

                // if cit1hummoy value has the lowest difference
                if ($cit1hummoy < $cit2hummoy) {
                    $compareData['city1']['score'] = $compareData['city1']['score'] + 15;
                }

                // if cit2moy value has the lowest difference
                if ($cit2hummoy < $cit1hummoy) {
                    $compareData['city2']['score'] = $compareData['city2']['score'] + 15;
                }

                // COMPARING THE CLOUDS RATE
                // First city clouds rate total
                $total = 0;
                $total += $responseArray['citiesAll'][0]->list[$i]->clouds->all * $listSize;
                $cit1clmoy = $total / $listSize;
                $cit1clmoy = $cit1clmoy - 60;

                // First city humidity total
                $total = 0;
                $total += $responseArray['citiesAll'][1]->list[$i]->main->humidity * $listSize;
                $cit2clmoy = $total / $listSize;
                $cit2clmoy = $cit2clmoy - 60;

                // if cit1clmoy value is below 0, make it positive
                if ($cit1clmoy < 0) {
                    $cit1clmoy = $cit1clmoy * -1;
                }

                // if cit2clmoy value is below 0, make it positive
                if ($cit2clmoy < 0) {
                    $cit2clmoy = $cit2clmoy * -1;
                }

                // inject average values of hum
                $compareData['city1']['clouds'] = $cit1clmoy;
                $compareData['city2']['clouds'] = $cit2clmoy;

                // if cit1clmoy value has the lowest difference
                if ($cit1clmoy < $cit2clmoy) {
                    $compareData['city1']['score'] = $compareData['city1']['score'] + 10;
                }

                // if cit2moy value has the lowest difference
                if ($cit2clmoy < $cit1clmoy) {
                    $compareData['city2']['score'] = $compareData['city2']['score'] + 10;
                }

                $arr = get_defined_vars();
                $arr;
                // dd($compareData, $arr);

                if ($compareData['city1']['score'] > $compareData['city2']['score']) {
                    $responseArray['winner'] = $compareData['city1']['name']; // Winner!
                }
            }
            // dd('');
            // dd($listSize);
        } else {
            null;
        }


        return $this->json([$responseArray, $compareData]);
    }
}
