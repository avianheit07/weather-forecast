<?php

namespace App\Services;

use App\Api\OpenWeatherMapApi;
use App\Api\WeatherBitApi;
use Illuminate\Database\Eloquent\Model;

class ForecastService
{
    /**
     * @var OpenWeatherMapApi
     */
    private $openWeatherMapApi;
    /**
     * @var WeatherBitApi
     */
    private $weatherBitApi;
    /**
     * @var int
     */
    private $precision;

    public function __construct(
        OpenWeatherMapApi $openWeatherMapApi,
        WeatherBitApi $weatherBitApi
    ) {
        $this->openWeatherMapApi = $openWeatherMapApi;
        $this->weatherBitApi     = $weatherBitApi;
        $this->precision         = 10;
    }

    public function processForecast($city, $country): array
    {
        $weather = new $this->openWeatherMapApi();
        $result  = $weather->setQueryParam([
            'q'     => "{$city},{$country}",
            'units' => 'metric'
        ])->send();

        $weather2 = new $this->weatherBitApi;
        $result2 = $weather2->setQueryParam([
            'city' => "{$city},{$country}"
        ])->send();

        $processedResult1 = $weather->processValues($result);
        $processedResult2 = $weather2->processValues($result2);

        $tempAvg          = $this->averageOfNumericValues('temp', $processedResult1, $processedResult2);
        $humidityAvg      = $this->averageOfNumericValues('humidity', $processedResult1, $processedResult2);
        $windSpeedAvg     = $this->averageOfNumericValues('wind_speed', $processedResult1, $processedResult2);
        $weatherSummary   = $this->summaryWeather('weather', $processedResult1, $processedResult2);

        return [
            'temp'       => $tempAvg,
            'humidity'   => $humidityAvg,
            'wind_speed' => $windSpeedAvg,
            'weather'    => $weatherSummary
        ];
    }

    protected function averageOfNumericValues($key, ...$dataArr): ?string
    {
        $totalCtr = 0;
        $totalValue = 0;

        foreach ($dataArr as $arr) {
            $totalValue = bcadd($totalValue, $arr[$key], $this->precision);
            $totalCtr++;
        }

        return bcdiv($totalValue, $totalCtr, $this->precision);
    }

    protected function summaryWeather($key, ...$dataArr): array
    {
        $weather = [];

        foreach ($dataArr as $arr) {
            foreach ($arr[$key] as $item) {
                $desc = $item->description;
                if (!in_array(strtolower($desc), $weather)) {
                    $weather[] = ucwords($desc);
                }
            }
        }

        return $weather;
    }

}
