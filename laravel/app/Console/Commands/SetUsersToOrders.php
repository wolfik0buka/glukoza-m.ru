<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\User;

class SetUsersToOrders extends Command
{

	protected $name = 'setUsersToOrders';

	protected $description = 'Поиск и привязка пользователей к заказам';


	public function __construct()
	{
		parent::__construct();
	}


	public function fire()
	{
        $users = User::all();

        Order::where('user_id', -1)
            ->where('onmedi_order_id', 0)
            ->get()
            ->each(function($order) use ($users) {

                $this->info($order->id);

                if (!str_contains($order->email, ['glukoza'])) {
                    $user = $users->where('email', $order->email)->first();
                } else {
                    $user = false;
                }

                if ($user) {
                    $order->user_id = $user->id;
                    $order->save();
                } else {
                    $user = $users->where('phone', $order->phone)->first();
                    if ($user) {
                        $order->user_id = $user->id;
                        $order->save();
                    }
                }

            });
	}


}
