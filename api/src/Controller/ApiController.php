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
        $treshTemp = 27; // Treshold of the wanted temp
        $treshHum = 60; // Treshold of the wanted humidity
        $treshClouds = 15; // Treshold of the wanted clouds rate

        // This array returns API call response in the good format
        $responseArray['city1today'] = ['icon' => null, 'name' => null, 'country' => null, 'temp' => null, 'humidity' => null, 'clouds' => null, 'wind' => null];
        $responseArray['city2today'] = ['icon' => null, 'name' => null, 'country' => null, 'temp' => null, 'humidity' => null, 'clouds' => null, 'wind' => null];
        $responseArray['city1avg'] = [];
        $responseArray['city2avg'] = [];
        $responseArray['citywinner'] = ['name' => null, 'country' => null, 'score' => null, 'tempavg' => null, 'humavg' => null, 'cloudsavg' => null];
        $responseArray['cityloser'] = ['name' => null, 'country' => null, 'score' => null, 'tempavg' => null, 'humavg' => null, 'cloudsavg' => null];
        $compareData = array(); // This array is only used in the algorythm

        // API's here
        $owm_base = "https://api.openweathermap.org/data/2.5/";
        $api_key = $parameterBag->get("API_KEY_SECRET");

        // First API calls
        $getWeather1 = file_get_contents($owm_base . "weather?q=" . $city1 . "&units=metric&appid=" . $api_key, true);
        $getWeather2 = file_get_contents($owm_base . "weather?q=" . $city2 . "&units=metric&appid=" . $api_key, true);

        $compareData = [json_decode($getWeather1), json_decode($getWeather2)]; // decode JSON received by the call to make the final calls after

        // Second API calls
        $getWeatherFull1 = file_get_contents($owm_base . "forecast?lat=" . $compareData[0]->coord->lat . "&lon=" . $compareData[0]->coord->lon . "&units=metric&appid=" . $api_key, true);
        $getWeatherFull2 = file_get_contents($owm_base . "forecast?lat=" . $compareData[1]->coord->lat . "&lon=" . $compareData[1]->coord->lon . "&units=metric&appid=" . $api_key, true);

        $compareData = [json_decode($getWeather1), json_decode($getWeather2), json_decode($getWeatherFull1), json_decode($getWeatherFull2)]; // Final Array format

        // Save data needed into responseData Array
        $responseArray['city1today'] = ['icon' => $compareData[0]->weather[0]->icon, 'name' => $compareData[0]->name, 'country' => $compareData[0]->sys->country, 'temp' => $compareData[0]->main->temp, 'humidity' => $compareData[0]->main->humidity, 'clouds' => $compareData[0]->clouds->all, 'wind' => $compareData[0]->wind->speed];
        $responseArray['city2today'] = ['icon' => $compareData[1]->weather[0]->icon, 'name' => $compareData[1]->name, 'country' => $compareData[1]->sys->country, 'temp' => $compareData[1]->main->temp, 'humidity' => $compareData[1]->main->humidity, 'clouds' => $compareData[1]->clouds->all, 'wind' => $compareData[1]->wind->speed];

        // Algorythm part
        if ($compareData[0]->name != $compareData[2]->city->name || $compareData[1]->name != $compareData[3]->city->name) {
            return null; // error
        }

        $listSize = count($compareData[2]->list); // value size of the weather list
        $compareData['city1full'] = $compareData[2];
        $compareData['city2full'] = $compareData[3];

        $temp1 = 0; // Total temp value of 1st city
        $temp2 = 0; // Total temp value of 2nd city
        $hum1 = 0; // Total humidity value of 1st city
        $hum2 = 0; // Total humidity value of 2nd city
        $clouds1 = 0; // Total clouds rate value of 1st city
        $clouds2 = 0; // Total clouds rate value of 2nd city

        // Calculate all the totals values we need
        for ($i = 0; $i < $listSize; $i++) {
            $temp1 += $compareData['city1full']->list[$i]->main->temp;
            $temp2 += $compareData['city2full']->list[$i]->main->temp;
            $hum1 += $compareData['city1full']->list[$i]->main->humidity;
            $hum2 += $compareData['city2full']->list[$i]->main->humidity;
            $clouds1 += $compareData['city1full']->list[$i]->clouds->all;
            $clouds2 += $compareData['city2full']->list[$i]->clouds->all;
        }

        $compareData['average'] = ['temp1' => $temp1, 'temp2' => $temp2, 'hum1' => $hum1, 'hum2' => $hum2, 'clouds1' => $clouds1, 'clouds2' => $clouds2];


        // Treatment for every values
        foreach ($compareData['average'] as $key => $value) {
            $value = $value / 40; // Calculate all the average

            // if total values are below zero
            if ($value < 0) {
                $value = $value * -1;
            } else {
                null;
            }

            $compareData['average'][$key] = $value;
        }

        unset($i, $value, $key); // Removing previous operations variables that where created

        // Puting average values into the responseArray
        $responseArray['city1avg'] = ['temp1' => $compareData['average']['temp1'], 'hum1' => $compareData['average']['hum1'], 'clouds1' => $compareData['average']['clouds1']];
        $responseArray['city2avg'] = ['temp2' => $compareData['average']['temp2'], 'hum2' => $compareData['average']['hum2'], 'clouds2' => $compareData['average']['clouds2']];

        // Calculate the offset between the recommanded values and the one that weather has
        $temp1 = $compare->calculateOffset($compareData['average']['temp1'], $treshTemp);
        $temp2 = $compare->calculateOffset($compareData['average']['temp2'], $treshTemp);
        $hum1 = $compare->calculateOffset($compareData['average']['hum1'], $treshHum);
        $hum2 = $compare->calculateOffset($compareData['average']['hum2'], $treshHum);
        $clouds1 = $compare->calculateOffset($compareData['average']['clouds1'], $treshClouds);
        $clouds2 = $compare->calculateOffset($compareData['average']['clouds2'], $treshClouds);
        // THESE VALUES ARE NOW OFFSET VALUES!

        // Assign points on the city that has the lowest offset
        $compareData['city1score'] = 0;
        $compareData['city2score'] = 0;

        if ($temp1 < $temp2) {
            $compareData['city1score'] += 20;
        } elseif ($temp2 < $temp1) {
            $compareData['city2score'] += 20;
        }

        if ($hum1 < $hum2) {
            $compareData['city1score'] += 15;
        } elseif ($hum2 < $hum1) {
            $compareData['city2score'] += 15;
        }

        if ($clouds1 < $clouds2) {
            $compareData['city1score'] += 10;
        } elseif ($clouds2 < $clouds1) {
            $compareData['city2score'] += 10;
        }

        // $compare->assignPoints($temp1, $temp2, $compareData['city1score'], $compareData['city2score'], 20);

        // Determine the winner and the loser
        if ($compareData['city1score'] > $compareData['city2score']) {
            $responseArray['citywinner'] = ['name' => $compareData[0]->name, 'country' => $compareData[0]->sys->country, 'score' => $compareData['city1score'], 'tempavg' => $compareData['average']['temp1'], 'humavg' => $compareData['average']['hum1'], 'cloudsavg' => $compareData['average']['clouds1']];
            $responseArray['cityloser'] = ['name' => $compareData[1]->name, 'country' => $compareData[1]->sys->country, 'score' => $compareData['city2score'], 'tempavg' => $compareData['average']['temp2'], 'humavg' => $compareData['average']['hum2'], 'cloudsavg' => $compareData['average']['clouds2']];
        } elseif ($compareData['city2score'] > $compareData['city1score']) {
            $responseArray['citywinner'] = ['name' => $compareData[1]->name, 'country' => $compareData[1]->sys->country, 'score' => $compareData['city2score'], 'tempavg' => $compareData['average']['temp2'], 'humavg' => $compareData['average']['hum2'], 'cloudsavg' => $compareData['average']['clouds2']];
            $responseArray['cityloser'] = ['name' => $compareData[0]->name, 'country' => $compareData[0]->sys->country, 'score' => $compareData['city1score'], 'tempavg' => $compareData['average']['temp1'], 'humavg' => $compareData['average']['hum1'], 'cloudsavg' => $compareData['average']['clouds1']];
        }

        // dd(get_defined_vars());

        return $this->json($responseArray);
    }
}
