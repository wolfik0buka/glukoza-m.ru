<?php namespace App\Services\CdekOrdersManagement\Traits;

use App\Models\Order;
use App\Services\CdekOrdersManagement\Exceptions\NotPickupPointException;
use CdekSDK\Common;
use CdekSDK\Common\Sender as CdekSender;
use CdekSDK\Common\Address;
use CdekSDK\Requests;
use CdekSDK\CdekClient;

trait TraitCdekOrderCreate
{


    /**
     * @return \Response
     */
    public function create()
    {
        $this->client = new CdekClient('b295324557ddb85ff1cc9f02697aa93a', 'e9074b34045ca97b0d174c45e7e1a3b7');

        $this->cdekOrder = new Common\Order([
          'Number' => $this->order->number_formatted,
          'SendCity' => Common\City::create([
            'Code' => 137, // Санкт-Петербург
          ]),
          'RecCity' => Common\City::create([
            'Code' => $this->order->city_id,
          ]),
          'RecipientName' => $this->order->fio,
          'RecipientEmail' => $this->order->email,
          'RecipientCompany' => 'СИНГЕР-МЕД, ООО',
          'RecipientCurrency' => 'RUB',
          'Phone' => $this->order->phone,
          'TariffTypeCode' => $this->getTariffTypeCode(),
        ]);

        if ($this->isDeliveryToAddress()) {
            $this->setOrderAddress();
            // $this->cdekOrder->addService(Common\AdditionalService::create(Common\AdditionalService::SERVICE_DELIVERY_TO_DOOR));
        }
        if ($this->isPickupPoint()) {
            $this->setOrderPvz();
        }

        $this->cdekOrder->addPackage($this->getPackage());

        $this->cdekOrder->setSender($this->getSender());

        $request = new Requests\DeliveryRequest([
          'Number' => $this->order->number_formatted,
        ]);

        $request->addOrder($this->cdekOrder);

        $response = $this->client->sendDeliveryRequest($request);
        //var_export($response);
        

        foreach ($response->getOrders() as $order) {
            Order::where('id', $this->order->id)
              ->update([
                'delivery_track_id' => $order->getDispatchNumber(),
              ]);
        }

        if ($response->hasErrors()) {
            return response()->json([
              'success' => false,
              'response' => $response,
            ], 201);
        }

        return response()->json([
          'success' => true,
        ], 201);
    }


    /**
     * @return \CdekSDK\Common\Sender
     */
    protected function getSender()
    {
        return CdekSender::create([
            'Company' => 'ООО «Сингер-Мед»',
            //'Name'    => 'Контактное лицо',
            'Phone'   => $this->getSenderPhone()
        ])
            ->setAddress(
                Address::create([
                    "Street" => "улица Сикейроса",
                    "House" => "дом 10 корпус 4 литера А ТК «Бульвар»",
                    "Flat" => "секция 30"
                ])
            );
    }


    /**
     * @return string
     */
    protected function getSenderPhone()
    {
        // Default
        return '+7 (812) 244-34-74';
    }


    /**
     * @return \CdekSDK\Common\Package
     */
    public function getPackage()
    {
        $package = Common\Package::create([
          'Number' => $this->order->number_formatted,
          'BarCode' => $this->order->number_formatted,
          'Weight' => $this->order->weight,
          'SizeA' => $this->order->size_x,
          'SizeB' => $this->order->size_y,
          'SizeC' => $this->order->size_z,
          'Comment' => 'Медтовары',
        ]);

        $package->addItem(new Common\Item([
          'WareKey' => $this->order->number_formatted,
          'Cost' => $this->order->getPaymentSum(),
          'Payment' => $this->isOrderPrepayed() ? 0 : $this->order->getPaymentSum(),
          'Weight' => $this->order->weight,
          'Amount' => 1,
          'Comment' => 'Медтовары',
        ]));

        return $package;
    }


    /**
     * @return bool|int
     */
    protected function getTariffTypeCode()
    {
        $code = false;
        if ($this->isPickupPoint()) {
            $code = 136;
        }
        if ($this->isDeliveryToAddress()) {
            $code = 137;
        }

        return $code;
    }


    /**
     * Установка пункта выдачи
     */
    protected function setOrderPvz()
    {
        $this->cdekOrder->setAddress(Common\Address::create([
          'PvzCode' => $this->getPickupPointId(),
        ]));
    }


    /**
     * @return mixed
     * @throws NotPickupPointException
     */
    protected function getPickupPointId()
    {
        if (!$this->order->deliveryPickupPoint) {
            throw new NotPickupPointException();
        }
        return $this->order->deliveryPickupPoint->point_id;
    }


    /**
     * Установка адреса доставки
     * @return void
     */
    protected function setOrderAddress()
    {
        $this->cdekOrder->setAddress(Common\Address::create([
          'Street' => $this->order->adr_street,
          'House' => $this->order->adr_house,
          'Flat' => $this->order->adr_flat,
        ]));
    }


    /**
     * В заказе доставка в пункт выдачи?
     * @return bool
     */
    protected function isPickupPoint()
    {
        return +$this->order->delivery === 6
          || +$this->order->delivery === 7
          || +$this->order->delivery === 11;
    }


    /**
     * В заказе доставка по адресу?
     * @return bool
     */
    protected function isDeliveryToAddress()
    {
        return +$this->order->delivery === 2
          || +$this->order->delivery === 3;
    }


    /**
     * Заказ уже оплачен?
     * @return bool
     */
    protected function isOrderPrepayed()
    {
        return +$this->order->status_pay === 1;
    }


}