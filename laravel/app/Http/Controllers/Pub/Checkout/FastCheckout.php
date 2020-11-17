<?php namespace App\Http\Controllers\Pub\Checkout;

use App\Http\Controllers\Controller;
use App\Models\BodyOrder;
use App\Models\Order;
use App\Models\Tovar;
use App\Services\SmsSender;
use Carbon\Carbon;
use Illuminate\Http\Request;


class FastCheckout extends Controller
{

    public function index(Request $request)
    {
        $order = $this->createOrder($request->order);

        if ($order) {
            if ($this->addProductsToOrder($order->id, $request->order)) {
                $this->sendSmsToBuyer($order->phone);
                return $this->responseOk();
            } else {
                $this->responseError();
            }
        } else {
            return $this->responseError();
        }
    }


    protected function responseOk()
    {
        session()->forget('basket');
        session()->forget('qt');

        return response()->json('ok', 200);
    }


    protected function responseError()
    {
        return response()->json('error', 500);
    }


    protected function createOrder($orderRequest)
    {
        if ($orderRequest['phone']) {
            return Order::create([
                'phone' => phone_format($orderRequest['phone']),
                'fio' => $orderRequest['fio'],
                'number' => Checkout::getOrderNumber(),
                'date_order' => Carbon::now()->toDateTimeString()
            ]);
        } else {
            return false;
        }
    }


    protected function sendSmsToBuyer($phone)
    {
        (new SmsSender)
            ->setPhone($phone)
            ->setMessage('Спасибо за заказ. Ожидайте звонка или смс')
            ->send();
    }


    protected function addProductsToOrder($order_id, $requestOrder)
    {
        $products = Tovar::with('tovar_1c_att')
            ->whereIn('id', collect($requestOrder['products'])->map(function($item){ return $item['id']; })->toArray() )
            ->get()
            ->map(function ($product) use ($order_id, $requestOrder) {
                $priceFrom1C = $product->tovar_1c_att ? $product->tovar_1c_att->price : false;
                return [
                    'parent' => $order_id,
                    'id_tovar' => $product->id,
                    'price' => $product->podzakaz ? $product->price : $priceFrom1C,
                    'amount' => collect($requestOrder['products'])->where('id', $product->id)->first()['amount'],
                    'created_at' => Carbon::now()->toDateTimeString(),
                ];
            })
            ->toArray();

        return BodyOrder::insert($products);
    }

}
