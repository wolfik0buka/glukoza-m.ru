<?php namespace App\Http\Controllers\Admin;

use App\Events\OnOrderStatusChanged;
use App\Http\Controllers\Admin\Sms\SmsOrderApproved;
use App\Http\Controllers\Admin\Sms\SmsOrderChanged;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pub\Checkout\Checkout;
use App\Models\BodyOrder;
use App\Models\Order;
use App\Models\Point;
use App\Models\Tovar;
use App\Services\SmsSender;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Orders extends Controller
{

    public function index(Request $request)
    {
        $date_from = $request->get('from', $this->getMostOldOrderInWorkDate());
        $date_to = $request->get('to', Carbon::now()->addDay(2)->endOfDay()->toDateTimeString());

        return response()->json([
            'orders' => $this->getAllOrdersBetween($request->get('from'), $request->get('to')),
            'from' => $date_from,
            'to' => $date_to,
        ], 200);
    }


    public function add()
    {
        $order = Order::create([
            'date_order' => Carbon::now()->toDateTimeString(),
            'phone' => '+7',
            'number' => Checkout::getOrderNumber(),
            'delivery' => 12,
        ]);

        return response()->json([
            'id' => $order->id,
        ], 200);
    }


    protected function getMostOldOrderInWorkDate() {
        return Order::whereIn('status', [1,2])
            ->where('created_at', '>', Carbon::parse('2019-01-01')->startOfDay()->toDateTimeString())
            ->orderBy('created_at', 'asc')
            ->select('created_at')
            ->first()
            ->created_at;
    }


    protected function getAllOrdersBetween($from, $to)
    {
        setlocale(LC_ALL, "ru_RU.UTF-8");

        $date_from = $from ? $from : $this->getMostOldOrderInWorkDate();
        $date_to = $to ? $to : Carbon::now()->endOfDay()->toDateTimeString();

        return Order::with('productLinks')
            ->where('created_at', '>', Carbon::parse($date_from)->startOfDay()->toDateTimeString())
            ->where('created_at', '<', Carbon::parse($date_to)->endOfDay()->toDateTimeString())
            ->orderBy('number', 'desc')
            ->get()
            ->map(function (Order $order) {

                $order->order_sum = $order->productLinks
                    ->filter(function($productLink) {
                        return !Tovar::isServiceProduct($productLink->id_tovar);
                    })
                    ->reduce(function ($memo, $link) {
                        return $memo + ($link->price * $link->amount);
                    });

                if (!$order->manager_id) {
                    $order->manager_id = -1;
                }
                $order->order_sum_raw = $order->order_sum;
                $order->order_sum = number_format($order->order_sum, 2, '.', '&#x202F;');
                $order->comment_my = str_replace("\n", '<br>', $order->comment_my);
                $order->glukoza_number_formatted = $order->glukozaNumberFormatted();

                unset($order['adr']);
                unset($order['adr_flat']);
                unset($order['adr_house']);
                unset($order['adr_street']);
                unset($order['comment']);
                unset($order['date_of_cur']);
                unset($order['date_of_delivery']);
                unset($order['del']);
                unset($order['deleted_at']);
                unset($order['delivery_days']);
                unset($order['delivery_track_id']);
                unset($order['delivery_track_sended_at']);
                unset($order['paylink_sended_at']);
                unset($order['payment_attempt_at']);
                unset($order['payment_hash']);
                unset($order['post_index']);
                unset($order['send_invoice_at']);
                unset($order['productLinks']);
                unset($order['size_x']);
                unset($order['size_y']);
                unset($order['size_z']);
                unset($order['sms_changed_at']);
                unset($order['status_agreed_at']);
                unset($order['status_pay_at']);
                unset($order['status_at']);
                unset($order['transferred_to_delivery_at']);
                unset($order['time_intervals']);
                unset($order['weight']);
                unset($order['post_id']);
                unset($order['payment_approved_at']);
                unset($order['sberbank_order_id']);
                unset($order['sms_payment_done_at']);
                unset($order['first_interaction_at']);
                unset($order['cour_id']);
                unset($order['delivery_price']);
                unset($order['dop_fld']);
                unset($order['sms']);

                return $order;
            })
            ->groupBy(function ($order) {
                return (int)strftime('%Y%m', strtotime($order->created_at));
            })
            ->map(function ($orders, $month) {
                return [
                    'name' => $month,
                    'date' => collect($orders)->first()->date_order,
                    'days' => collect($orders)
                        ->groupBy(function ($order) {
                            return (int)strftime('%d', strtotime($order->created_at));
                        })
                        ->map(function ($orders, $day) {
                            return [
                                'name' => $day,
                                'date' => collect($orders)->first()->date_order,
                                'orders' => $orders,
                            ];
                        }),
                ];
            });
    }


    /**
     * @param $id
     * @return Order | Collection
     */
    public function getSingle($id)
    {
        try {
            $order = Order::with('productLinks.product.tovar_1c_att', 'deliveryPickupPoint')->findOrFail($id);
            $order->glukoza_number_formatted = $order->glukozaNumberFormatted();

            return $order;

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'error' => true,
                'message' => 'Заказ не найден',
            ]);
        }

    }


    public function update(Request $request)
    {
        $order = Order::where('id', $request->get('id'))->first();

        $except = [
            'is_done',
            'is_success',
            'number_formatted',
            'glukoza_number_formatted',
            'product_links',
            'delivery_pickup_point',
            'payment_hash',
            'sberbank_order_id',
            'payment_attempt_at',
            'first_interaction_at',
        ];

        $order_status_changed = +$order->status !== +$request->get('status');

        $order->update($request->except($except));

        if ($order_status_changed) {
            event(new OnOrderStatusChanged($order));
        }
    }





    public function setPoint(Request $request)
    {
        Point::where('parent', $request->get('parent'))->delete();

        $payload = $request->except('index', 'point_coord', 'time', 'expanded');
        $point = Point::create($payload);

        return $point;
    }


    public function sendSmsApproved(Request $request)
    {
        SmsOrderApproved::send($request->get('order_id'));
    }


    public function sendSmsCdekTrackingCode(Request $request)
    {
        $order = Order::where('id', $request->get('order_id'))
            ->first();

        if ($order->onmedi_order_id === 0 && $order->delivery_track_id) {
            (new SmsSender)
                ->setPhone($order->phone)
                ->setMessage('Ваш заказ №' . $order->cour_id . ' отправлен. Отслеживание: https://glukoza-med.ru/track-orders/' . $order->delivery_track_id)
                ->send();
        }
    }


    public function sendSmsPaymentDone(Request $request)
    {
        $order = Order::where('id', $request->get('order_id'))->first();
        if ($order->onmedi_order_id === 0) {
            (new SmsSender)
                ->setPhone($order->phone)
                ->setMessage('Оплата по заказу №' . $order->number_formatted . ' получена, заказ в обработке')
                ->send();
        }
        $order->sms_payment_done_at = Carbon::now()->toDateTimeString();
        $order->save();
    }


    public function sendSmsOrderTransferredToDelivery(Request $request)
    {
        $order = Order::where('id', $request->get('order_id'))->first();

        if (!$order->transferred_to_delivery_at) {
            if ($order->onmedi_order_id === 0) {
                (new SmsSender)
                    ->setPhone($order->phone)
                    ->setMessage('Заказ №' . $order->number_formatted . ' передан в службу доставки')
                    ->send();
            }
            $order->transferred_to_delivery_at = Carbon::now()->toDateTimeString();
            $order->save();
        }
    }


    public function sendSmsOrderReadyToPickup(Request $request)
    {
        $order = Order::where('id', $request->get('order_id'))->first();

        if ($order->onmedi_order_id === 0) {
            (new SmsSender)
                ->setPhone($order->phone)
                ->setMessage('Ваш заказ №'.$order->number_formatted.' готов к получению в ПВЗ.')
                ->send();
        }
        $order->ready_to_pickup_at = Carbon::now()->toDateTimeString();
        $order->save();
    }


    public function sendSmsOrderChanged(Request $request)
    {
        SmsOrderChanged::send($request->get('order_id'));
    }


    public function clone($id)
    {
        $orderSource = Order::with('productLinks', 'deliveryModel')
            ->where('id', $id)
            ->first();

        $order = Order::create([
            "manager_id" => $orderSource->manager_id,
            "is_preorder" => $orderSource->is_preorder,
            "onmedi_order_id" => $orderSource->onmedi_order_id,
            "number" => Checkout::getOrderNumber(),
            "user_id" => $orderSource->user_id,
            "payment_type_id" => $orderSource->payment_type_id,
            "date_order" => $orderSource->date_order,
            "fio" => $orderSource->fio,
            "phone" => $orderSource->phone,
            "email" => $orderSource->email,
            "comment" => $orderSource->comment,
            "adr" => $orderSource->adr,
            "adr_street" => $orderSource->adr_street,
            "adr_house" => $orderSource->adr_house,
            "adr_flat" => $orderSource->adr_flat,
            "delivery" => $orderSource->delivery,
            "delivery_point_id" => $orderSource->delivery_point_id,
            "city_id" => $orderSource->city_id,
            "city_name" => $orderSource->city_name,
            "post_index" => $orderSource->post_index,
            "weight" => $orderSource->weight,
            "size_x" => $orderSource->size_x,
            "size_y" => $orderSource->size_y,
            "size_z" => $orderSource->size_z
        ]);

        $order->save();

        foreach($orderSource->productLinks as $productLink) {
            BodyOrder::create([
                "parent" => $order->id,
                "id_tovar" => $productLink->id_tovar,
                "price" => $productLink->price,
                "amount" => $productLink->amount,
            ]);
        }

        return response()->json(["id" => $order->id]);
    }

}