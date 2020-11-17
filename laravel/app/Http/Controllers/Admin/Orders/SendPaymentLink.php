<?php namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Services\SmsSender;
use App\Models\Order;
use Carbon\Carbon;
use Mail;

class SendPaymentLink extends Controller
{

    public function index($order_id)
    {
        $order = Order::where('id', $order_id)->first();
        $order = Order::makeHash($order);

        try {
            $this->sendSms($order);
            $this->sendEmail($order);
            $order->update([
                "paylink_sended_at" => Carbon::now()->toDateTimeString()
            ]);

            return response()->json([
                'success' => true
            ], 200);

        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'send' => $ex->getMessage()
            ], 200);
        }
    }


    protected function sendEmail($order)
    {
        if ($order->email) {
            Mail::send('public.mail.mailPaymentLink', compact('order'), function($message) use($order) {
                return $message
                    ->to($order->email)
                    ->subject('Заказ #'.$order->number_formatted.' - ссылка на оплату');
            });
        }
    }


    protected function sendSms($order)
    {
        if ($order->phone) {
            (new SmsSender)
                ->setPhone($order->phone)
                ->setMessage(implode(' ',
                    [
                        'Ссылка на оплату заказа №'.$order->number_formatted.':',
                        env('WEB_URL', 'https://glukoza-med.ru').'/payment/'.$order->payment_hash
                    ]
                ))
                ->send();
        }
    }

    
}