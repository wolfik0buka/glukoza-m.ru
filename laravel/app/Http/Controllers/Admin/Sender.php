<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class Sender extends Controller
{
    public function add_post_id(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = 3;
        $order->status_agreed = 1;
        $order->post_id = $request->post_id;
        $order->save();
        $date['order'] = $order;

        Mail::send('admin.mail.post_index', $date, function ($message) use ($order) {
            $message
                ->to($order->email)
                ->subject('Заказ №' . str_repeat('0', 5 - strlen($order->number)) . $order->number . '\\' . strftime('%y', time()));
        });

        /* СМС клиенту - начало */
        $bad_simbol = array(' ', '+', '(', ')', '-');
        $phone = str_replace($bad_simbol, '', $order->phone);
        $txt = urlencode('Заказ №' . str_repeat('0', 5 - strlen($order->number)) . $order->number . '\\' . strftime('%y', time()) . ' отправлен. Ваш почтовый идентификатор: ' . $order->post_id);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://smsc.ru/sys/send.php?login=isin_glukoza&psw=SubarU96&phones=' . $phone . '&mes=' . $txt . '&charset=utf-8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        curl_close($ch);
        /* СМС клиенту - конец */

        return ('ok');
    }
}