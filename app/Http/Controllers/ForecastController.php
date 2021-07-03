<?php

namespace App\Http\Controllers;

use App\Api\OpenWeatherMapApi;
use App\Api\WeatherBitApi;
use App\Services\ForecastService;
use Illuminate\Http\Request;

class ForecastController extends Controller
{
    /**
     * @var ForecastService
     */
    private $forecastService;

    public function __construct(ForecastService $forecastService)
    {
        $this->forecastService = $forecastService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = [];

        if ($request->has('city') && $request->get('city')) {
            $city    = $request->get('city');
            $country = $request->get('country');

            $result = $this->forecastService->processForecast($city, $country);
        }

        return view('welcome', compact('result'));
    }
}
