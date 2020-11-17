<?php namespace App\Services\Cdek;

use GuzzleHttp\Client;

class CityPickupPoints
{

    public static function getByCityId($city_id)
    {
        $request = (new Client())->post("https://onmedi.ru/services/city_pickup_points/$city_id",[
            'headers' => [
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
            ],
            'json' => [
                'city_id' => $city_id
            ]
        ]);

        $result = json_decode($request->getBody());

        return collect($result)
            ->map(function($point) {
                return $point;
            });
    }


}