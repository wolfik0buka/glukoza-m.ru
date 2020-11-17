<?php declare(strict_types=1);
namespace App\Http\Controllers\Pub\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;

class PaymentSuccess extends Controller
{

    public function index()
    {
        $this->makeOrderPaymentSuccess();

        return view('public.payment.paymentSuccess');
    }


    protected function makeOrderPaymentSuccess()
    {
        if (isset($_GET['orderId']))
        {
            Order::where('sberbank_order_id', $_GET['orderId'])
                ->update([
                    'status_pay' => 1,
                    'status_pay_at' => Carbon::now()->toDateTimeString()
                ]);
        }
    }

}