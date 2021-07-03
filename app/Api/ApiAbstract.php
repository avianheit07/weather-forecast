<?php

namespace App\Api;

use GuzzleHttp\Client;
use phpDocumentor\Reflection\Types\Mixed_;

abstract class ApiAbstract implements ApiInterface
{
    private $headers;
    private $guzzleClient;
    private $method;
    protected $baseUrl;
    protected $tokenValue;
    protected $tokenKey;
    private $queryParam;

    public function __construct()
    {
        $this->guzzleClient = new Client;
        $this->method       = 'get';
        $this->headers      = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json'
        ];
    }

    /**
     * Array data
     *  temperature -> temp
     *  wind speed -> wind_speed
     *  sunrise -> sunrise
     *  sunset -> sunset
     *  weather -> weather_main
     *  weather -> weather_description
     * @return array
     */
    abstract public function processValues($result): array;

    public function getBodyResponse($response)
    {
        if ($response) {
            $body   = (string) $response->getBody();
            return json_decode($body);
        }

        return false;
    }
    public function setBaseUrl($baseUrl): ApiAbstract
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function setTokenKey($tokenKey): ApiAbstract
    {
        $this->tokenKey = $tokenKey;
        return $this;
    }

    public function setTokenValue($tokenValue): ApiAbstract
    {
        $this->tokenValue = $tokenValue;
        return $this;
    }

    public function send()
    {
        if (!$this->baseUrl) {
            return false;
        }

        $url = $this->baseUrl;

        if ($this->tokenKey && $this->tokenValue) {
            $url .= "?{$this->tokenKey}={$this->tokenValue}";
        }

        if ($this->queryParam) {
            $url .= "&{$this->queryParam}";
        }

        $context = [
            'url' => $url
        ];

        try {
            $response = $this->guzzleClient->{$this->method}($url);

            $context['response']      = $response->getBody();
            $context['response_code'] = $response->getStatusCode();
            return $this->getBodyResponse($response);
            // context for logging
        } catch (\Exception $e) {
            // log error
            // log error message with context;
            return false;
        }
    }

    public function setQueryParam(array $params): ApiAbstract
    {
        $this->queryParam = implode('&', array_map(
            function ($value, $key) {
                return sprintf("%s=%s", $key, $value);
            },
            $params,
            array_keys($params)
        ));

        return $this;
    }

}
