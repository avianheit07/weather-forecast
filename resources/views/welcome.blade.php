<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Weather App</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <style>
            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .wrapper {
                text-align: center;
            }
            .title {
                font-size: 84px;
            }
            .required {
                color: red;
            }
            .custom-invalid-feedback {
                width: 100%;
                margin-top: .25rem;
                font-size: .875em;
                color: #dc3545;
            }
        </style>
        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="wrapper">
                <div class="container">
                    <h1 class="title text-center">
                        Weather App
                    </h1>
                    <form id="get-forecast" action="{{ route('forecast.index') }}" method="GET">
                        @csrf
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" name="city" value="{{ old('city', request('city')) }}" id="city" placeholder="Enter city">


                            <div class="custom-invalid-feedback" role="alert">
                                {{ $messageError }}
                            </div>
                        </div>
                        <div class="form-group my-2 mb-5">
                            <label for="country">Country</label>
                            <input type="text" class="form-control" name="country" value="{{ old('country', request('country')) }}" id="country" placeholder="Enter Country">
                        </div>

                        <div class="form-group">
                            <div class="col text-center">
                                <button class="btn btn-primary" type="submit">check</button>
                                <a href="{{ route('forecast.index') }}">
                                    <button class="btn btn-warning" type="button">clear</button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="container">
                    @if($result)
                        <div class="card border-dark mt-3">
                            <div class="card-header">Current Weather</div>
                            <div class="card-body text-dark">
                                <h5 class="card-title">{{ request('city') }}, {{ request('country') }}</h5>
                                <p class="card-text">Wind: {{ number_format($result['wind_speed'], 2)  }}</p>
                                <p class="card-text">Temperature: {{ number_format($result['temp'], 2) }}</p>
                                <p class="card-text">Humidity: {{ number_format($result['humidity'], 2) }}</p>
                                <p class="card-text">Forecast: {{ implode(" / ", $result['weather']) }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
