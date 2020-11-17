<?php

namespace App\Listeners;

use App\Events\OnAddOrderEvent;
use App\Models\Order;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Bitrix24\Models\Task;
use Carbon\Carbon;

class AddOrderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OnAddOrderEvent  $event
     * @return void
     */
    public function handle(OnAddOrderEvent $event)
    {
        if ($event->is_preorder) {
            $this->createPreorderTaskInBitrix24($event->order);
        }
    }



    protected function createPreorderTaskInBitrix24(Order $order)
    {
        if ($order->bitrix_task_id === 0) {

            $product_name = '';
            if (count($order->productLinks) > 0) {
                $product_name = collect($order->productLinks)->first()->product->name;
            }

            $task_id = (new Task)
                ->setTitle(implode(" ", [
                    $order->glukozaNumberFormatted(),
                    $product_name
                ]))
                ->setDescription(implode("\r\n", [
                        "[URL=https://glukoza-med.ru/admin_new/index.php?page=order&id=".$order->id."]".$order->glukozaNumberFormatted()."[/URL]",
                        $order->fio,
                        'Телефон: '.($order->phone ? $order->phone : 'Не указан'),
                        'Email: '.($order->email ? $order->email : 'Не указан'),
                        'Город: '.($order->city_name ? $order->city_name.' (предположительно)' : 'Не удалось определить'),

                ]))
                ->setAuditors([1])
                ->setDeadline(Carbon::now()->addDays(3))
                ->setResponsible(19)
                ->create();

            $order->update([
                "bitrix_task_id" => $task_id
            ]);

        }
    }



}
