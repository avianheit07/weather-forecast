<?php

namespace App\Api;

class WeatherBitApi extends ApiAbstract
{
    public function __construct()
    {
        $this->setBaseUrl('https://api.weatherbit.io/v2.0/current');
        $this->setTokenKey('key');
        $this->setTokenValue('334d41231b6f45f0a8497000e9ec2583');
        parent::__construct();
    }

    public function processValues($result): array
    {
        $result = $result->data[0];
        return [
            'temp'       => $result->temp,
            'humidity'   => $result->rh,
            'wind_speed' => $result->wind_spd,
            'weather'    => [$result->weather],
        ];
    }
}
