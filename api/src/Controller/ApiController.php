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
        $treshHhum = 60; // Treshold of the wanted humidity
        $treshClouds = 15; // Treshold of the wanted clouds rate

        // This array returns API call response in the good format
        $responseArray['city1today'] = ['icon' => null, 'name' => null, 'country' => null, 'temp' => null, 'humidity' => null, 'clouds' => null, 'wind' => null];
        $responseArray['city2today'] = ['icon' => null, 'name' => null, 'country' => null, 'temp' => null, 'humidity' => null, 'clouds' => null, 'wind' => null];
        $responseArray['cityavg1'] = [];
        $responseArray['cityavg2'] = [];
        $responseArray['citywinner'] = ['name' => null, 'country' => null];
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
        if ($compareData[0]->name == $compareData[2]->city->name && $compareData[1]->name == $compareData[3]->city->name) {
            $listSize = count($compareData[2]->list); // value size of the weather list

            $temp1 = 0; // Total temp value of the first city
            $temp2 = 0; // Total temp value of the second city
            $hum1 = 0; // Total humidity value of the first city
            $hum2 = 0; // Total humidity value of the second city
            $clouds1 = 0; // Total clouds rate value of the first city
            $clouds2 = 0; // Total clouds rate value of the second city

            // Calculate all the totals values we need
            for ($i = 0; $i < $listSize; $i++) {
                $temp1 += $compareData[2]->list[$i]->main->temp;
                $temp2 += $compareData[3]->list[$i]->main->temp;
                $hum1 += $compareData[2]->list[$i]->main->humidity;
                $hum2 += $compareData[3]->list[$i]->main->humidity;
                $clouds1 += $compareData[2]->list[$i]->clouds->all;
                $clouds2 += $compareData[3]->list[$i]->clouds->all;
            }

            $compareData['totals'] = ['temp1' => $temp1, 'temp2' => $temp2, 'hum1' => $hum1, 'hum2' => $hum2, 'clouds1' => $clouds1, 'clouds2' => $clouds2];

            foreach ($compareData['totals'] as $key => $value) {
                # code...
            }

            // dd(get_defined_vars());

            $temp1 = $compare->calculateOffset($temp1 / 40, $treshTemp);

            $compare->ifValBelowZero($temp1);

            dd(get_defined_vars(), $temp1);
        } else {
            null;
        }
    }
}
