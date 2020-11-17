<?php namespace App\Services\Cdek;

use GuzzleHttp\Client;

class City
{

    public static function search($query)
    {
        if (mb_strlen($query) > 3) {
            $request = (new Client())->post("https://onmedi.ru/search/cities",[
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                ],
                'json' => [
                    'query' => $query
                ]
            ]);

            $result = json_decode($request->getBody());

            return collect($result)->map(function($city) {
                return [
                    'id' => $city->id,
                    'cityName' => explode(',', $city->name)[0],
                    'name' => $city->name
                ];
            });
        } else {
        }
    }


}