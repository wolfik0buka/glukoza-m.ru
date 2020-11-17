<?php declare(strict_types=1);

namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Sberbank\Sberbank;
use Carbon\Carbon;
use Voronkovich\SberbankAcquiring\Exception\ActionException;



class RegisterOrderInSberbank extends Controller
{

    public $order;

    /**
     * @param int $order_id
     * @return array | string
     * array:2 [
     *   "orderId" => "34b76632-af72-75b1-34b7-663204b2401b"
     *   "formUrl" => "https://3dsec.sberbank.ru/payment/merchants/sbersafe/payment_ru.html?mdOrder=34b76632-af72-75b1-34b7-663204b2401b"
     * ]
     */
    public function index(int $order_id)
    {
        $this->getOrder($order_id);

        $this->checkPaymentType();

        try {
            return $this->attemptRegister();
        } catch (ActionException $e) {
            return $e->getCode().' '.$e->getMessage();
        }
    }


    protected function attemptRegister()
    {
        $attempt_time = Carbon::now();
        $this->order->payment_attempt_at = $attempt_time->toDateTimeString();

        $response = Sberbank::create($this->order->number_formatted.'-'.$attempt_time->timestamp, (int) ($this->order->getPaymentSum() * 100));

        is_array($response)
            ? $this->order->update([ 'sberbank_order_id' => $response['orderId'] ])
            : false;

        return $response;
    }


    protected function checkPaymentType()
    {
        if ($this->order->payment_type_id!==2) {
            return redirect()
                ->back()
                ->with('error', 'Неверный статус метода оплаты заказа. Обратитесь к вашему менеджеру');
        }
    }


    /**
     * @param int $order_id
     */
    protected function getOrder(int $order_id)
    {
        $this->order = Order::with('productLinks.product.tovar_1c_att', 'deliveryPickupPoint')
            ->findOrFail($order_id);
    }


}