<?php namespace App\Http\Controllers\Pub;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\Settings;
use App\Models\Tovar;
use Illuminate\Http\Request;
use App\Services\Cdek\CalcPvzPrice;
use App\Services\Cdek\CalcDeliveryPrice;
use App\Http\Controllers\Controller;

class DeliveryController extends Controller
{

    protected $order_id = false;
    protected $delivery_id = false;
    protected $order_products_sum = false;
    protected $city_id = false;

    public $order;
    public $delivery_model;
    public $delivery_product;
    public $price;



    /**
     * Request must contain:
     * @param $order_id int
     * @param $delivery_id int
     * @param $order_products_sum int
     * @param $city_id int
     */
    public function index($order_id, $delivery_id, $order_products_sum, $city_id)
    {
        $this->order_id = (int) $order_id;
        $this->delivery_id = (int) $delivery_id;
        $this->order_products_sum = (int) $order_products_sum;
        $this->city_id = (int) $city_id;

        $this->getDeliveryModel();
        $this->getDeliveryProduct();
        $this->getOrder();

        switch ($this->delivery_id) {
            case 1: // Магазин Коменд. пр-т
                break;
            case 2: // Курьер РФ
                $this->modificationsCourierRussiaPrice();
                break;
            case 3: // Курьер Спб
                $this->delivery_product->price = config('settings')->find('deliverySpbCourierPrice')->value;
                break;
            case 4: // Почта РФ
                $this->delivery_product->price = config('settings')->find('deliveryRussianPostPrice')->value;
                break;
            case 5: // Магазин Сикейроса
                break;
            case 8: // Магазин Вологда
                break;
            case 11: // ПВЗ
                $this->modificationsPvzPrice();
                break;
            case 13: // Магазин Ладожская (Энергетиков)
                break;
            case 14: // Курьер день в день
                $this->delivery_product->price = config('settings')->find('deliverySpbExpressCourierPrice')->value;
                break;
        }

        return $this->delivery_product;
    }


    protected function modificationsPvzPrice()
    {
        // Если Спб
        if ($this->city_id===137) {

            // Если заказ более определенной суммы
            if ($this->order_products_sum > config('settings')->find('deliverySpbPvzFreeFrom')->value) {
                $this->delivery_product->price = 0;
            }

            // Если способ оплаты - картой на сайте
            elseif ($this->order->payment_type_id===2) {
                $this->delivery_product->price = 0;
            }

            // Прочие заказы
            else {
                $this->delivery_product->price = (int) config('settings')->find('deliverySpbPvzPrice')->value;
            }
        }

        // Другие города
        else {
            $calc = CalcPvzPrice::getPriceForCity($this->city_id);

            $calc_response = json_decode($calc->getContent());

            if ($calc_response->error) {
                $this->delivery_product->price = 0;
            } else {
                $this->delivery_product->price = +json_decode($calc->getContent())->payload->price;
                $this->modificationsPvzPriceOnmedi();
            }

        }
    }


    protected function modificationsPvzPriceOnmedi()
    {
        if ($this->order->onmedi_order_id > 0) {
            $this->delivery_product->price = $this->delivery_product->price - 70;
        }
    }


    protected function modificationsCourierRussiaPrice()
    {
        $calc = CalcDeliveryPrice::getPriceForCity($this->city_id);
        $this->delivery_product->price = json_decode($calc->getContent())->payload->price;
    }


    protected function getDeliveryModel()
    {
        $this->delivery_model = Delivery::where('id', $this->delivery_id)
            ->first();
    }


    protected function getDeliveryProduct()
    {
        $this->delivery_model->load('product.tovar_1c_att');
        $this->delivery_product = $this->delivery_model->product;
    }


    protected function getOrder()
    {
        $this->order = Order::where('id', $this->order_id)
            ->first();
    }

}