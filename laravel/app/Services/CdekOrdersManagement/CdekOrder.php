<?php namespace App\Services\CdekOrdersManagement;

use App\Models\Order;
use CdekSDK\CdekClient;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Orders;
use App\Services\CdekOrdersManagement\Traits\TraitCdekOrderCreate;

class CdekOrder extends Controller
{
    use TraitCdekOrderCreate;

    protected $client;
    protected $order;
    protected $glukozaOrderId;
    protected $cdekOrder;


    public function __construct()
    {
        $this->client = app(CdekClient::class);
    }


    /**
     * Step 1
     * @return \App\Services\CdekOrdersManagement\CdekOrder
     */
    public static function init()
    {
        return (new CdekOrder);
    }


    /**
     * Step 2
     * @param int $id
     * @return \App\Services\CdekOrdersManagement\CdekOrder
     */
    public function setGlukozaOrderId(int $id): self
    {
        $this->glukozaOrderId = $id;
        $this->getGlukozaOrder();

        return $this;
    }


    protected function getGlukozaOrder()
    {
        $this->order = Order::with('productLinks.product.tovar_1c_att', 'deliveryPickupPoint', 'deliveryModel')
          ->findOrFail($this->glukozaOrderId);

        $this->setOrderCityIfNotExist();
    }


    protected function setOrderCityIfNotExist()
    {
        if (!$this->order->city_id) {
            if (
                +$this->order->delivery === 3
                || +$this->order->delivery === 5
                || +$this->order->delivery === 6
                || +$this->order->delivery === 14
            ) {
                $this->order->city_id = 137;
                $this->order->city_name = 'Санкт-Петербург';
                $this->order->save();
            }
        }
    }


}