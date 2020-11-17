<?php namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Services\Sberbank\Sberbank;
use Illuminate\Http\Request;

class GetSberbankOrderStatus extends Controller
{

    public static function index(Request $request)
    {
        return Sberbank::status($request->get('sberbank_order_id'));
    }
}