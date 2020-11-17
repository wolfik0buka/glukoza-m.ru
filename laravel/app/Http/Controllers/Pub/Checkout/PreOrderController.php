<?php namespace App\Http\Controllers\Pub\Checkout;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Tovar;
use App\Models\BodyOrder;
use App\Events\OnAddOrderEvent;

class PreOrderController extends Controller
{

    public function index(Request $request)
    {
        $order = $this->createPreOrder($request);

        $this->createPreOrderProduct($request->get('product_id'), $order->id);

        $this->fireOrderAddEvent($order->id);
    }


    protected function fireOrderAddEvent($order_id)
    {
        $order = Order::with('productLinks.product')
            ->where('id', $order_id)
            ->first();

        event(new OnAddOrderEvent($order));
    }


    public function createPreOrder(Request $request)
    {
        return Order::create([
            'is_preorder' => 1,
            'phone' => phone_format($request->get('phone')),
            'fio' => $request->get('fio'),
            'number' => Checkout::getOrderNumber(),
            'date_order' => Carbon::now()->toDateTimeString()
        ]);
    }


    public function createPreOrderProduct($product_id, $order_id)
    {
        return BodyOrder::insert([
            'parent' => $order_id,
            'id_tovar' => $product_id,
            'amount' => 1,
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
    }

}