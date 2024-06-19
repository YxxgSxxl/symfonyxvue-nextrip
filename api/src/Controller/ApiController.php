<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use App\Service\CompareService; // Custom service that help comparing datas
use App\Service\UtilityService; // Custom service to help the algorythm init datas correctly

class ApiController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(string $message = null): JsonResponse
    {
        $message = "Welcome to the REST API of this project, to use the API, you can paste this after your DNS: /api/putACityHere1/putACityHere2. It will returns a lot of weather informations about the two cities provided, enjoy!";

        return $this->json($message);
    }

    #[Route('/api/{city1}/{city2}', name: 'app_api')]
    public function compare(string $city1, string $city2, HttpClientInterface $client, CompareService $compare, UtilityService $utility): JsonResponse
    {
        // This array returns API call response in the good format
        $responseArray = array();
        // $responseArray['city1today'] = ['icon' => null, 'name' => null, 'country' => null, 'temp' => null, 'humidity' => null, 'clouds' => null, 'wind' => null];
        $utility->constructCityResponseArray(
            $responseArray,
            'city1today',
            [
                'icon' => null,
                'name' => null,
                'country' => null,
                'temp' => null,
                'humidity' => null,
                'clouds' => null,
                'wind' => null
            ]
        );
        $utility->constructCityResponseArray(
            $responseArray,
            'city2today',
            [
                'icon' => null,
                'name' => null,
                'country' => null,
                'temp' => null,
                'humidity' => null,
                'clouds' => null,
                'wind' => null
            ]
        );

        $utility->constructCityResponseArray(
            $responseArray,
            'citywinner',
            [
                'name' => null,
                'country' => null,
                'score' => null,
                'tempavg' => null,
                'humavg' => null,
                'cloudsavg' => null,
                'windavg' => null
            ]
        );

        $utility->constructCityResponseArray(
            $responseArray,
            'cityloser',
            [
                'name' => null,
                'country' => null,
                'score' => null,
                'tempavg' => null,
                'humavg' => null,
                'cloudsavg' => null,
                'windavg' => null
            ]
        );

        $compareData = array(); // This array is only used in the algorithm

        // First API calls
        try {
            $getWeather1 = $client->request('GET', $utility->getWeatherUrl('weather', $city1, $utility->apiKey, $utility->apiBaseUrl))->toArray();
            $getWeather2 = $client->request('GET', $utility->getWeatherUrl('weather', $city2, $utility->apiKey, $utility->apiBaseUrl))->toArray();

            if ($getWeather1 == $getWeather2) {
                return new JsonResponse(['error' => ['message' => 'Le nom des villes entrées sont similaires', 'name' => 'Nom similaires']], 409);
            }
        } catch (Exception $e) {
            // TODO: Gérer toutes les erreurs
            return new JsonResponse(['error' => ['message' => 'OpenWeather est indisponible', 'name' => 'API indisponible']], 400);
        }

        // Verify if the API call received isn't in the good format
        // $compare->ifApiCallIsDeprecated($getWeather1, $getWeather2);

        if (
            !isset($getWeather1['weather']) ||
            !isset($getWeather1['weather'][0]['icon']) ||
            !isset($getWeather1['name']) ||
            !isset($getWeather1['sys']['country']) ||
            !isset($getWeather1['main']['temp']) ||
            !isset($getWeather1['main']['humidity']) ||
            !isset($getWeather1['clouds']) ||
            !isset($getWeather1['wind']['speed']) ||

            !isset($getWeather2['weather']) ||
            !isset($getWeather2['weather'][0]['icon']) ||
            !isset($getWeather2['name']) ||
            !isset($getWeather2['sys']['country']) ||
            !isset($getWeather2['main']['temp']) ||
            !isset($getWeather2['main']['humidity']) ||
            !isset($getWeather2['clouds']) ||
            !isset($getWeather2['wind']['speed'])
        ) {
            return new JsonResponse(['error' => ['message' => 'Une erreur est survenue', 'name' => 'Résultat requête API incorect']], 400);
        }

        $compareData = ['city1' => $getWeather1, 'city2' => $getWeather2];

        $utility->constructCityTodayResponseArray('city1today', $compareData['city1'], $responseArray);

        $utility->constructCityTodayResponseArray('city2today', $compareData['city2'], $responseArray);


        // Second API calls
        try {
            $getCity1WeatherUrl = $client->request('GET', $utility->getWeatherFullUrl('forecast', $compareData['city1'], $utility->apiKey, $utility->apiBaseUrl))->toArray();
            $getCity2WeatherUrl = $client->request('GET', $utility->getWeatherFullUrl('forecast', $compareData['city2'], $utility->apiKey, $utility->apiBaseUrl))->toArray();
        } catch (Exception $e) {
            // TODO: Gérer toutes les erreurs
            return new JsonResponse(['error' => ['message' => 'OpenWeather est indisponible']]);
        }

        $compareData = ['city1' => $getWeather1, 'city2' => $getWeather2, 'city1full' => $getCity1WeatherUrl, 'city2full' => $getCity2WeatherUrl];

        // if ($compareData[0]->name != $compareData[2]->city->name || $compareData[1]->name != $compareData[3]->city->name) {
        //     return null; // error
        // }

        // Algorythm part
        $listSize = count($compareData['city1full']['list']); // value size of the weather list
        $temp1 = 0; // Total temp value of 1st city
        $temp2 = 0; // Total temp value of 2nd city
        $hum1 = 0; // Total humidity value of 1st city
        $hum2 = 0; // Total humidity value of 2nd city
        $clouds1 = 0; // Total clouds rate value of 1st city
        $clouds2 = 0; // Total clouds rate value of 2nd city
        $wind1 = 0; // Total wind speed value of the 1st city
        $wind2 = 0; // Total wind speed value of the 2nd city

        // Calculate all the totals values we need
        for ($i = 0; $i < $listSize; $i++) {
            $temp1 += $compareData['city1full']['list'][$i]['main']['temp'];
            $temp2 += $compareData['city2full']['list'][$i]['main']['temp'];
            $hum1 += $compareData['city1full']['list'][$i]['main']['humidity'];
            $hum2 += $compareData['city2full']['list'][$i]['main']['humidity'];
            $clouds1 += $compareData['city1full']['list'][$i]['clouds']['all'];
            $clouds2 += $compareData['city2full']['list'][$i]['clouds']['all'];
            $wind1 += $compareData['city1full']['list'][$i]['wind']['speed'];
            $wind2 += $compareData['city2full']['list'][$i]['wind']['speed'];
        }

        $compareData['total'] = ['temp1' => $temp1, 'temp2' => $temp2, 'hum1' => $hum1, 'hum2' => $hum2, 'clouds1' => $clouds1, 'clouds2' => $clouds2, 'wind1' => $wind1, 'wind2' => $wind2];

        // Treatment for every average values
        foreach ($compareData['total'] as $key => $value) {
            $value = $value / $listSize; // Calculate all the average

            // if total values are below zero
            if ($value < 0) {
                $value = $value * -1; // TODO: Pas sûr car -20 se transforme en 20, et ça fausse le résultat
            } else {
                null;
            }

            $compareData['average'][$key] = $value;
        }

        // dump(memory_get_usage());

        // Calculate the offset between the recommanded values and the one that weather has
        $temp1 = $compare->calculateOffset($compareData['average']['temp1'], $compare::TRESH_TEMP);
        $temp2 = $compare->calculateOffset($compareData['average']['temp2'], $compare::TRESH_TEMP);
        $hum1 = $compare->calculateOffset($compareData['average']['hum1'], $compare::TRESH_HUM);
        $hum2 = $compare->calculateOffset($compareData['average']['hum2'], $compare::TRESH_HUM);
        $clouds1 = $compare->calculateOffset($compareData['average']['clouds1'], $compare::TRESH_CLOUDS);
        $clouds2 = $compare->calculateOffset($compareData['average']['clouds2'], $compare::TRESH_CLOUDS);

        // Assign points on the city that has the lowest offset
        $compareData['city1score'] = 0;
        $compareData['city2score'] = 0;

        $compare->assignPoints($temp1, $temp2, $compareData, $compare::TEMP_POINTS);
        $compare->assignPoints($hum1, $hum2, $compareData, $compare::HUM_POINTS);
        $compare->assignPoints($clouds1, $clouds2, $compareData, $compare::CLOUDS_POINTS);

        // Determine the winner and the loser
        $compare->determineWinLose($compareData, $responseArray);

        // Formating to make it readable by the front-end
        $responseArray['citiestoday'] = array(
            array($responseArray['city1today']),
            array($responseArray['city2today']),
        );

        // dd(get_defined_vars());
        return $this->json($responseArray);
    }
}
