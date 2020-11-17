<?php namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Mail;

class SendInvoice extends Controller
{

    public function index($order_id)
    {
        $order = Order::where('id', $order_id)->first();

        $pdf = (new PdfInvoice)
            ->setOrder($order->id)
            ->saveAndGetPath();
        try {
            $send = Mail::send('public.mail.mailOrderInvoice', [], function($message) use($order, $pdf) {
                $message
                     ->to($order->email)
                    ->attach($pdf)
                    ->subject('Заказ #'.$order->number_formatted.' - счет на оплату');
            });

            return response()->json([
                'success' => true,
                'send' => $send
            ], 200);

        } catch (\Exception $ex) {

            return response()->json([
                'success' => false,
                'send' => $ex->getMessage()
            ], 200);

        }
    }
    
}