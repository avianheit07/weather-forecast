<?php

namespace App\Api;

interface ApiInterface
{
    public function setBaseUrl($baseUrl);
    public function setTokenKey($tokenKey);
    public function setTokenValue($tokenValue);
}
