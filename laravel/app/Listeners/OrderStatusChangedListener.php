<?php

namespace App\Listeners;

use App\Events\OnOrderStatusChanged;
use App\Services\Bitrix24\Models\Task;
use App\Models\Order;

class OrderStatusChangedListener
{


    /**
     * @param  OnOrderStatusChanged  $event
     */
    public function handle(OnOrderStatusChanged $event)
    {
        $this->completeBitrix24TaskIfOrderComplete($event->order);
    }


    protected function completeBitrix24TaskIfOrderComplete(Order $order)
    {
        if ($order->bitrix_task_id > 0) {
            if ($order->status === 3 || $order->status === 4) {
                (new Task)->complete($order->bitrix_task_id);
            }
        }
    }


}