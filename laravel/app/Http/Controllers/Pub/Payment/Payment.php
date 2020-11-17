<?php declare(strict_types=1);
namespace App\Http\Controllers\Pub\Payment;

use App\Http\Controllers\Admin\Orders\RegisterOrderInSberbank;
use App\Services\Sberbank\Sberbank;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Order;

class Payment extends Controller
{


    public function index($hash = false)
    {
        if ($hash) {
            try {
                $order = Order::with('productLinks.product.tovar_1c_att', 'deliveryPickupPoint')
                    ->where('payment_hash', $hash)
                    ->firstOrFail();

                // Если заказ оплачен
                if ($order->status_pay===1) {
                    return redirect()->to('/payment/success');
                }

                // Если заказ имеет статус "Снят"
                if ($order->status===4) {
                    return (new PaymentLinkExpired())->index();
                }

                // Если ссылка на оплату отправлена более 5 дней назад
                if ($order->paylink_sended_at) {
                    $isNotValidLink = Carbon::parse($order->paylink_sended_at)->diffInDays(Carbon::now()) > 4;
                    if ($isNotValidLink) {
                        return (new PaymentLinkExpired())->index();
                    }
                }

            } catch (ModelNotFoundException $ex) { $order = false; }
        } else {
            $order = false;
        }

        return view('public.payment.payment', compact('order'));
    }


    public function redirectToSberbank($order_id)
    {
        $order = Order::where('id', $order_id)->firstOrFail();

        if ($order->sberbank_order_id) {
            if (!Sberbank::isDeclined($order->sberbank_order_id))
            {
                return redirect()
                    ->to(Sberbank::getApiURL().'/payment/merchants/sbersafe/payment_ru.html?mdOrder='.$order->sberbank_order_id);
            }
        }

        $register = (new RegisterOrderInSberbank())->index((int) $order_id);

        if (is_array($register)) {
            return redirect()->to($register['formUrl']);
        }

        if (is_string($register)) {
            return redirect()
                ->back()
                ->with('error', $register);
        }
    }


}