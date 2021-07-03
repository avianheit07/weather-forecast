<?php

namespace App\Services;

use App\Api\OpenWeatherMapApi;
use App\Api\WeatherBitApi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
        $city            = strtolower($city);
        $cityUnderscored = str_replace(" ", "-", $city);
        $weather         = new $this->openWeatherMapApi;
        $weather2        = new $this->weatherBitApi;
        $date            = Carbon::now()->format('Y-m-d');
        $dateWithTime    = Carbon::now();
        $cacheKey        = strtolower($cityUnderscored) . $date;

        if (Cache::has($cacheKey)) {
            $valueArr = Cache::get($cacheKey);
            $valueData = $valueArr['value'];
            $valueDate = $valueArr['date'];

            if ($dateWithTime->diffInHours($valueDate) <= 3) {
                return $valueData;
            }
        }

        $result  = $weather->setQueryParam([
            'q'     => "{$city},{$country}",
            'units' => 'metric'
        ])->send();

        $result2 = $weather2->setQueryParam([
            'city' => "{$city},{$country}"
        ])->send();

        $processedResult1 = $weather->processValues($result);
        $processedResult2 = $weather2->processValues($result2);

        $tempAvg          = $this->averageOfNumericValues('temp', $processedResult1, $processedResult2);
        $humidityAvg      = $this->averageOfNumericValues('humidity', $processedResult1, $processedResult2);
        $windSpeedAvg     = $this->averageOfNumericValues('wind_speed', $processedResult1, $processedResult2);
        $weatherSummary   = $this->summaryWeather('weather', $processedResult1, $processedResult2);

        $returnValue = [
            'temp'       => $tempAvg,
            'humidity'   => $humidityAvg,
            'wind_speed' => $windSpeedAvg,
            'weather'    => $weatherSummary
        ];

        Cache::add(strtolower($cityUnderscored) . Carbon::now()->format('Y-m-d'), [
            'date'  => Carbon::now(),
            'value' => $returnValue
        ]);

        return $returnValue;
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
