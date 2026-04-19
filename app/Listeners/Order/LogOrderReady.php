<?php

namespace App\Listeners\Order;

use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOrderReady
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $order = $event->order;

        app(ActivityService::class)->log(
            'order.ready',
            $order,
            "Order #{$order->order_number} ready.",
            [
                "total" => $order->total_amount
            ]
        );
    }
}
