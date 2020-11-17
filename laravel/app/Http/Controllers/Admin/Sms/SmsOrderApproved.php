<?php namespace App\Http\Controllers\Admin\Sms;

use App\Models\Order;
use App\Services\SmsSender;
use Carbon\Carbon;

class SmsOrderApproved extends SmsController
{

    public static function send($order_id)
    {
        $order = Order::with('productLinks')->find($order_id);

        $orderFullSum = collect($order->productLinks)
            ->sum(function ($link) {
                return $link->amount * $link->price;
            });

        $mess = '';

        switch ($order->delivery) {
            case (8): // Вологда
            case (1): // Комендантский
                $mess = 'Vash zakaz №' . $order->number_formatted . ' na summu ' . ($orderFullSum - $order->bonus) . ' gotov k vydache';
                break;
            case (5): // Озерки
                $mess = 'Vash zakaz №'.$order->number_formatted.' na summu '.($orderFullSum - $order->bonus).' gotov k vydache po adresu: ul.Sikejrosa 10 TK Bulvar, sekcija 4\2, pn-pt 11-19, sb 11-18';
                break;
            case (13): // Ладожская
                $mess = 'Vash zakaz №'.$order->number_formatted.' na summu '.($orderFullSum - $order->bonus).' gotov k vydache po adresu: SPb, pr.Energetikov 3 TK Ladozhskie Ryady, sekcija 73, pn-pt 11-19, sb 11-18';
                break;
            case (2): // Курьер РФ
            case (3): // Курьер Спб
            case (4): // Почта РФ
            case (11): // ПВЗ
            case (12): // Быстрый заказ
            case (14): // День в день
                $mess = 'Ваш заказ №' . $order->number_formatted . ' подтвержден и передан в обработку';
                break;
        }

        (new SmsSender)
            ->setPhone($order->phone)
            ->setMessage($mess)
            ->send();

        $order->update(["sms" => Carbon::now()->toDateTimeString()]);
    }

}