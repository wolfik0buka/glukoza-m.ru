<?php declare(strict_types=1);
namespace App\Http\Controllers\Pub\Payment;

use App\Http\Controllers\Controller;

class PaymentFailed extends Controller
{


    public function index()
    {
        return view('public.payment.paymentFail');
    }


}