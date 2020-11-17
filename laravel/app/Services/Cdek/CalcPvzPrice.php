<?php namespace App\Services\Cdek;

use Carbon\Carbon;

class CalcPvzPrice
{

    public static function getPriceForCity($city_id)
    {
        try {
            $calc = new CalculatePriceDeliveryCdek();

            //Авторизация
            $calc->setAuth(config('services.cdek.account'), config('services.cdek.password'));

            //город-отправитель
            $calc->setSenderCityId(137);

            //город-получатель
            $calc->setReceiverCityId($city_id);

            //устанавливаем дату планируемой отправки
            $calc->setDateExecute(Carbon::now()->addDay(1)->format('Y-m-d'));

            //устанавливаем тариф по-умолчанию
            $calc->setTariffId('136');

            //устанавливаем режим доставки
            $calc->setModeDeliveryId(4);

            //добавляем места в отправление
            $calc->addGoodsItemBySize(0.1, 10, 10, 10);

            $calc->calculate();

            if ($calc->calculate() === true) {

                $payload = collect($calc->getResult()['result'])
                    ->toArray();

                $payload['price'] = +$payload['price'] + 70;

                return response()
                    ->json(['error' => false, 'payload' => $payload], 200);

            } else {
                $err = $calc->getError();
                $payload = [];

                if( isset($err['error']) && !empty($err) ) {
                    foreach($err['error'] as $e) {
                        $payload[] = [
                            'code' => $e['code'],
                            'text' => $e['text']
                        ];
                    }
                }
                return response()->json(['error' => true, 'errors' => $payload]);
            }
        } catch (\Exception $e) {

            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

}
