<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use App\Service\CompareService; // Custom service that help comparing datas

class ApiController extends AbstractController
{

    private array $apiConfig;
    private string $apiBaseUrl;
    private string $apiKey;

    // TODO Mettre l'intelligence dans un service
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->apiConfig = $parameterBag->get("api");
        $this->apiBaseUrl = $this->apiConfig['url'];
        $this->apiKey = $this->apiConfig['secret'];
    }

    #[Route('/', name: 'app_index')]
    public function index(string $message = null): JsonResponse
    {
        $message = "Welcome to the REST API of this project, to use the API, you can paste this after your DNS: /api/putACityHere1/putACityHere2. It will returns a lot of weather informations about the two cities provided, enjoy!";

        return $this->json($message);
    }

    #[Route('/api/{city1}/{city2}', name: 'app_api')]
    public function compare(string $city1, string $city2, ParameterBagInterface $parameterBag, CompareService $compare, HttpClientInterface $client): JsonResponse
    {
        $treshTemp = 27; // Treshold of the wanted temp
        $treshHum = 60; // Treshold of the wanted humidity
        $treshClouds = 15; // Treshold of the wanted clouds rate

        // This array returns API call response in the good format
        $responseArray['city1today'] = ['icon' => null, 'name' => null, 'country' => null, 'temp' => null, 'humidity' => null, 'clouds' => null, 'wind' => null];
        $responseArray['city2today'] = ['icon' => null, 'name' => null, 'country' => null, 'temp' => null, 'humidity' => 10, 'clouds' => null, 'wind' => null];
        $responseArray['citywinner'] = ['name' => null, 'country' => null, 'score' => null, 'tempavg' => null, 'humavg' => null, 'cloudsavg' => null, 'windavg' => null];
        $responseArray['cityloser'] = ['name' => null, 'country' => null, 'score' => null, 'tempavg' => null, 'humavg' => null, 'cloudsavg' => null, 'windavg' => null];
        $compareData = array(); // This array is only used in the algorythm

        // API's here
        // First API calls
        try {
            // TODO: Faire les appels via le HttpClient
            $getWeather1 = $client->request('GET', $this->apiBaseUrl . "weather?q=" . $city1 . "&units=metric&appid=" . $this->apiKey)->toArray();
        } catch (Exception $e) {
            // TODO: Gérer toutes les erreurs
            return new JsonResponse(['error' => ['message' => 'OpenWeather est indisponible']]);
        }

        if (
            !isset($getWeather1['weather']) ||
            !isset($getWeather1['weather']['icon'])
        ) {
            // TODO : Error
        }

        $getWeather2 = file_get_contents($this->apiBaseUrl . "weather?q=" . $city2 . "&units=metric&appid=" . $this->apiKey, true);

        // TODO: Utiliser des noms pour les clés plutôt que des numéros
        $compareData = ['city1' => $getWeather1, json_decode($getWeather2)]; // decode JSON received by the call to make the final calls after

        // Second API calls
        $getCity1WeatherUrl = $this->getWeatherUrl($compareData['city1']);
        $getCity2WeatherUrl = $this->getWeatherUrl($compareData[1]);

        $getWeatherFull1 = file_get_contents($getCity1WeatherUrl, true);
        $getWeatherFull2 = file_get_contents($getCity2WeatherUrl, true);

        $compareData = [$getWeather1, json_decode($getWeather2), json_decode($getWeatherFull1), json_decode($getWeatherFull2)]; // Final Array format

        // Save data needed into responseData Array
        // TODO: Mettre en forme
        $responseArray['city1today'] = [
            'icon' => $compareData['city1']['weather']['icon'],
            'name' => $compareData[0]->name,
            'country' => $compareData[0]->sys->country,
            'temp' => $compareData[0]->main->temp,
            'humidity' => $compareData[0]->main->humidity,
            'clouds' => $compareData[0]->clouds->all,
            'wind' => $compareData[0]->wind->speed
        ];

        $responseArray['city2today'] = ['icon' => $compareData[1]->weather[0]->icon, 'name' => $compareData[1]->name, 'country' => $compareData[1]->sys->country, 'temp' => $compareData[1]->main->temp, 'humidity' => $compareData[1]->main->humidity, 'clouds' => $compareData[1]->clouds->all, 'wind' => $compareData[1]->wind->speed];

        // if ($compareData[0]->name != $compareData[2]->city->name || $compareData[1]->name != $compareData[3]->city->name) {
        //     return null; // error
        // }

        // Algorythm part
        $listSize = count($compareData[2]->list); // value size of the weather list
        $compareData['city1full'] = $compareData[2];
        $compareData['city2full'] = $compareData[3];

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
            $temp1 += $compareData['city1full']->list[$i]->main->temp;
            $temp2 += $compareData['city2full']->list[$i]->main->temp;
            $hum1 += $compareData['city1full']->list[$i]->main->humidity;
            $hum2 += $compareData['city2full']->list[$i]->main->humidity;
            $clouds1 += $compareData['city1full']->list[$i]->clouds->all;
            $clouds2 += $compareData['city2full']->list[$i]->clouds->all;
            $wind1 += $compareData['city1full']->list[$i]->wind->speed;
            $wind2 += $compareData['city2full']->list[$i]->wind->speed;
        }

        $compareData['total'] = ['temp1' => $temp1, 'temp2' => $temp2, 'hum1' => $hum1, 'hum2' => $hum2, 'clouds1' => $clouds1, 'clouds2' => $clouds2, 'wind1' => $wind1, 'wind2' => $wind2];

        // Treatment for every average values
        foreach ($compareData['average'] as $key => $value) {
            // TODO: Utiliser le vrai nombre de ligne ($listSize ?)
            $value = $value / 40; // Calculate all the average

            // if total values are below zero
            if ($value < 0) {
                $value = $value * -1; // TODO: Pas sûr car -20 se transforme en 20, et ça fausse le résultat
            } else {
                null;
            }

            $compareData['average'][$key] = $value;
        }

        //dump(memory_get_usage());
        unset($i, $value, $key); // Removing previous operations variables that where created
        //dump(memory_get_usage());

        // Calculate the offset between the recommanded values and the one that weather has
        // TODO Mettre les tresh idéaux dans le service en const
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

        // TODO: Mettre les points dans le service en const
        $compare->assignPoints($temp1, $temp2, $compareData, 20);
        $compare->assignPoints($hum1, $hum2, $compareData, 15);
        $compare->assignPoints($clouds1, $clouds2, $compareData, 10);

        // Determine the winner and the loser
        $compare->determineWinLose($compareData, $responseArray);

        // dd(get_defined_vars());

        // Formating to make it readable by the front-end
        $responseArray['citiestoday'] = array(
            array("score" => $responseArray['city1today']),
            array("score" => $responseArray['city2today']),
        );

        unset($responseArray['city1today'], $responseArray['city2today']); // Removing previous operations variables that where created

        return $this->json($responseArray);
    }


    public function getWeatherUrl(object $compareDataElement): string
    {
        $getWeatherParams = [
            'lat' => $compareDataElement->coord->lat,
            'lon' => $compareDataElement->coord->lon,
            'units' => 'metric',
            'appid' => $this->apiKey
        ];
        return $this->apiBaseUrl . '?' . http_build_query($getWeatherParams);
    }
}
