<?php namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Cdek\CdekPrint;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PdfCdek extends Controller
{

    public function index($order_id)
    {
        try {
            $order = Order::findOrFail($order_id);

            return (new CdekPrint)
                ->setDate(Carbon::parse($order->date_order)->toDateString())
                ->setTrackNumber($order->delivery_track_id)
                ->xmlRequest();

        } catch (ModelNotFoundException $e) {
            return 'Заказ под номером '.$order_id.' не найден';
        }
    }


}