<?php

namespace Vnnit\Core\Support;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class CommonHelper
{
    public function getSectionCode()
    {
        if (\is_null(Request::route())) return '';
        $routeName = explode('.', Request::route()->getName());
        return trim(head($routeName));
    }

    public function callApi($method, $url, $params = [])
    {
        $fullUrl = config('laka.api_address')."{$url}";
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            // 'Content-Type' => 'application/x-www-form-urlencoded',
            'token' => config('laka.api_token')
        ])->{$method}($fullUrl, $params);

        if ($response->ok())
            return $response->collect();

        return $response->body();
    }
}
