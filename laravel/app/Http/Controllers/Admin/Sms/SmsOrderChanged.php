<?php

namespace App\Http\Controllers\Admin\Sms;

use App\Models\Order;
use App\Services\SmsSender;
use Carbon\Carbon;

class SmsOrderChanged extends SmsController
{

    public static function send($order_id)
    {
        $order = Order::with('productLinks', 'deliveryModel')
            ->where('id', $order_id)
            ->first();

        $orderFullSum = collect($order->productLinks)->sum(function ($link) {
            return $link->amount * $link->price;
        });

        $message = [
            'Заказ №' . $order->number_formatted . ' обновлен. ',
            'Сумма ' . $orderFullSum . ' руб. ',
        ];

        if ($order->delivery !== 10 && $order->delivery !== 12) {
            $message[] = trim($order->deliveryModel->name_full) . '. ';
        }


        if ($order->onmedi_order_id === 0) {
            (new SmsSender)
                ->setPhone($order->phone)
                ->setMessage(implode('', $message))
                ->send();
        }

        $order->sms_changed_at = Carbon::now()->toDateTimeString();
        $order->save();
    }
}