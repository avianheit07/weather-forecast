<?php

namespace App\Api;

class OpenWeatherMapApi extends ApiAbstract
{
    public function __construct() {
        $this->setBaseUrl('https://api.openweathermap.org/data/2.5/weather');
        $this->setTokenKey('appid');
        $this->setTokenValue('33c61d0ad3579d7b9f723c9a38cacd32');
        parent::__construct();
    }

    public function processValues($result): array
    {
        return [
            'temp'       => $result->main->temp,
            'humidity'   => $result->main->humidity,
            'wind_speed' => $result->wind->speed,
            'weather'    => $result->weather
        ];
    }
}
