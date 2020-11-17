<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OnAddOrderEvent extends Event
{
    use SerializesModels;

    public $order_id;
    public $is_preorder;
    public $order;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->order_id = $order->id;
        $this->is_preorder = (int) $order->is_preorder === 1;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
