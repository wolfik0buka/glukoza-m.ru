<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BodyOrder;
use App\Models\Order;
use App\Models\Settings;
use App\Models\Tovar;
use Illuminate\Http\Request;
use App\Services\Cdek\CalcDeliveryPrice;
use App\Services\Cdek\CalcPvzPrice;

class OrderProducts extends Controller
{

    public function remove($id)
    {
        BodyOrder::destroy($id);
    }


    public function update(Request $request)
    {
        $orderProduct = BodyOrder::find($request->id);

        $orderProduct->update([
            'price' => $request->price,
            'amount' => $request->amount
        ]);
        $orderProduct->load('product.tovar_1c_att');

        return $orderProduct;
    }


    public function add(Request $request)
    {
        if ($request->has('product_id'))
        {
            $product = Tovar::with('tovar_1c_att')
                ->where('id', $request->product_id)
                ->firstOrFail();

            $link = BodyOrder::create([
                'parent' => $request->order_id,
                'id_tovar' => $product->id,
                'amount' => 1,
                'price' => $request->has('price')
                    ? $request->price
                    : $product->real_price
            ]);

            $link->load('product.tovar_1c_att');

            return $link;
        }
    }

}
