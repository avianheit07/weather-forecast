<?php

namespace App\Api;

class WeatherBitApi extends ApiAbstract
{
    public function __construct()
    {
        $this->name = 'WeatherBitApi';
        $this->setBaseUrl('https://api.weatherbit.io/v2.0/current');
        $this->setTokenKey('key');
        $this->setTokenValue('334d41231b6f45f0a8497000e9ec2583');
        parent::__construct();
    }

    public function processValues($result): array
    {
        return [
            'temp'       => $result->data[0]->temp ?? 0,
            'humidity'   => $result->data[0]->rh ?? 0,
            'wind_speed' => $result->data[0]->wind_spd ?? 0,
            'weather'    => isset($result->data[0]) ? [$result->data[0]->weather] : [],
        ];
    }
}
