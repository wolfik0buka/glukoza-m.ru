<?php namespace App\Http\Controllers\Pub\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pub\UsersController;
use App\Models\BodyOrder;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderCapture;
use App\Models\Point;
use App\Services\SmsSender;
use App\User;
use Carbon\Carbon;
use App\Services\BonusSystem;
use Swift_RfcComplianceException;
use Input;
use Illuminate\Support\Facades\Log;
use Mail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Checkout extends Controller
{
    protected $order_session;
    protected $order_model;
    protected $delivery_model;
    protected $sum_by_products;
    protected $sum_delivery;
    protected $sum_full;
    protected $is_create_user_account = false;


    public function __construct()
    {
        $this->order_session = session()->get('order');

        $this->is_create_user_account = session()->get('isCreateUserAccount', false);

    }


    /**
     * Checkout page
     * @return \Illuminate\View\View
     */
    public function index()
	{
        $user_id = session()->has('user') ? session()->get('user.id') : 'false';
        $bonus_balance = session()->has('user') ? (new BonusSystem(session()->get('user.id')))->current_balance : 'false';

        $seo = self::seo()
            ->setTitle('Корзина - оформление заказа')
            ->all();

		return view('public.checkout.pageCheckout', compact('user_id', 'bonus_balance', 'seo'));
	}


    /**
     * Update order data in session
     * @return string
     */
    public function updateOrder()
    {
        session(['order' => Input::get('order') ]);
        session(['isCreateUserAccount' => Input::get('isCreateUserAccount')]);

        session([
            'order.user_id' => session()->has('user')
                ? session()->get('user.id')
                : false
        ]);

        return json_encode(session()->get('order'));
	}


    public function doneOrder()
    {
        $this->createOrder();
        $this->addProductsToOrder();
        $this->addDeliveryProductToOrder();
        $this->addDeliveryPointToOrder();
        $this->calcOrderSum();
        $this->sendSmsToBuyer();
        $this->sendMailToBuyer();

        // create new user account (if user approove)
        if ($this->is_create_user_account) {
            $user_id = User::findByEmail($this->order_model->email);
            $user_id = $user_id ? $user_id : User::findByPhone($this->order_model->phone);
            $user_id = $user_id ? $user_id : UsersController::createFromOrder($this->order_model->id);

            if ($user_id > 0) {
                $this->order_model->update(['user_id' => $user_id]);
            }
        }
	}


    public static function getOrderNumber()
    {
        return (Order::whereYear('created_at', '=', date('Y'))->max('number') + 1);
	}


    protected function createOrder()
    {
        $this->order_model = Order::create([
            'number' => self::getOrderNumber(),
            'date_order' => Carbon::now()->toDateTimeString(),
            'fio' => $this->order_session['fio'],
            'phone' => phone_format($this->order_session['phone']),
            'email' => mb_strtolower($this->order_session['email']),
            'comment' => $this->order_session['comment'],
            'adr' => $this->order_session['adr'],
            'dop_fld' => $this->order_session['dop_fld'],
            'date_of_delivery' => $this->order_session['date_of_delivery'],
            'time_intervals' => $this->order_session['time_intervals'],
            'payment_type_id' => $this->order_session['payment_type_id'],
            'delivery' => is_null($this->order_session['delivery'])
                ? 12
                : $this->order_session['delivery'],
            'city_id' => $this->order_session['city_id'],
            'city_name' => $this->order_session['city_name'],
            'post_index' => $this->order_session['post_index'] ? $this->order_session['post_index'] : -1,
            'bonus' => $this->order_session['bonus'],
        ]);
    }


    protected function addProductsToOrder()
    {
        $toInsert = collect($this->order_session['products'])
            ->map(function($product) {
                return [
                    'parent' => $this->order_model->id,
                    'id_tovar' => $product['id'],
                    'price' => $product['price'],
                    'amount' => $product['amount'],
                    'created_at' => Carbon::now()->toDateTimeString(),
                ];
            })
            ->toArray();

        return BodyOrder::insert($toInsert);
	}


	protected function addDeliveryProductToOrder()
    {
        try {
            $this->delivery_model = Delivery::with('product')
                ->findOrFail($this->order_session['delivery']);

            if ($this->delivery_model->product) {
                BodyOrder::create([
                    'parent' => $this->order_model->id,
                    'id_tovar' => $this->delivery_model->product->id,
                    'price' => $this->order_session['delivery_price'],
                    'amount' => 1
                ]);
            }
        } catch (ModelNotFoundException $ex) {
            // Error handling code
        }
    }


    protected function addDeliveryPointToOrder()
    {
        if ($this->order_session['delivery'] === 11) {
            $deliveryPoint = $this->order_session['deliveryPoint'];

            Point::create([
                'id_obl' => $deliveryPoint['id_obl'],
                'city_id' => $deliveryPoint['city_id'],
                'city_name' => $deliveryPoint['city_name'],
                'point_id' => $deliveryPoint['point_id'],
                'point_address' => $deliveryPoint['point_address'],
                'point_name' => $deliveryPoint['point_name'],
                'point_phone' => $deliveryPoint['point_phone'],
                'point_way' => $deliveryPoint['point_way'],
                'point_work' => $deliveryPoint['point_work'],
                'parent' => $this->order_model->id,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
        }
	}


	protected function calcOrderSum()
    {
        $this->sum_by_products = BodyOrder::where('parent', $this->order_model->id)
            ->get()
            ->sum(function ($link) {
                return $link->amount * $link->price;
            });

        $used_bonus = $this->order_session['bonus']
            ? $this->order_session['bonus']
            : 0;

        $this->sum_full = (float)$this->sum_by_products - (float)$used_bonus;
    }


    protected function sendSmsToBuyer()
    {
        (new SmsSender)
            ->setPhone($this->order_model->phone)
            ->setMessage('Спасибо за заказ. Сумма: '.$this->sum_full.' руб. Ждите звонка или смс. Магазин Глюкоза.')
            ->send();
    }


    protected function sendMailToBuyer()
    {
        $order = $this->order_model;
        $order->delivery_price = $this->sum_delivery;

        try {
            Mail::send('public.mail.order_shop', [
                'order' => $order,
                'sum_full' => $this->sum_full
            ], function ($message) use ($order) {
                $message
                    ->to($order->email)
                    ->subject('Заказ №'.str_repeat('0', 5 - strlen($order->number)).$order->number . '\\' . strftime('%y', time()) . ' от ' . strftime('%d.%m.%Y', strtotime($order->created_at)));
            });
        } catch (\Exception $ex) {
            //
        }

    }


}
