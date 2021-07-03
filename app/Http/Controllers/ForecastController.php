<?php

namespace App\Http\Controllers;

use App\Services\ForecastService;
use App\Http\Requests\CheckRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $result       = [];
        $messageError = '';

        if ($request->has('city') && $request->get('city')) {
            $city    = $request->get('city');
            $country = $request->get('country');

            if (ctype_alpha($city)) {
                $result = $this->forecastService->processForecast($city, $country);
            } else {
                $result       = [];
                $messageError = "Use text only.";
            }
        }

        return view('welcome', compact('result' , 'messageError'));
    }
}
