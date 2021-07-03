<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiWeatherResult extends Model
{
    protected $fillable = [
        'url',
        'api_name',
        'response',
        'status',
        'ip_address',
    ];
}
