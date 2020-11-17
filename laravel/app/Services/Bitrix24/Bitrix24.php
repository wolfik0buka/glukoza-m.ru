<?php

namespace App\Services\Bitrix24;
use Bitrix24\Bitrix24 as Bitrix24Api;
use Bitrix24\CRM\Deal\Deal;
use Bitrix24\Exceptions\Bitrix24Exception;
use Bitrix24\Task\Items;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Http\Controllers\Controller;

class Bitrix24 extends Controller
{

    protected $api_url = "https://glukoza.bitrix24.ru/rest/13/trpx38eonqr35l22/";

    protected $response = false;

    
    protected function request(string $method, array $params = [])
    {
        $this->response = json_decode(file_get_contents($this->makeRequestUrl($method, $params)));
        return $this->getResponse();
    }


    protected function getResponse()
    {
        return $this->response;
    }


    protected function makeRequestUrl(string $method, array $params = []) : string
    {
        return $this->api_url.$method.'/?'.http_build_query($params);
    }
    




    // protected static function bitrixRequestByPage(int $page = 1)
    // {
    //     return json_decode(file_get_contents("https://glukoza.bitrix24.ru/rest/13/trpx38eonqr35l22/crm.deal.list/?O[CREATED_DATE]=desc&F[]=&P[LOAD_TAGS]=Y&P[NAV_PARAMS][nPageSize]=50&P[NAV_PARAMS][iNumPage]=" . $page));
    // }

}