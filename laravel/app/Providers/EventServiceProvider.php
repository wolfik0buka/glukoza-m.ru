<?php namespace App\Providers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'App\Events\OnAddOrderEvent' => [
			'App\Listeners\AddOrderListener',
		],
        'App\Events\OnOrderStatusChanged' => [
            'App\Listeners\OrderStatusChangedListener',
        ],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

		Order::updated(function($order) {
		    if (!$order->first_interaction_at) {
		        $order->update([ 'first_interaction_at' => Carbon::now()->toDateTimeString() ]);
            }
        });
	}

}
