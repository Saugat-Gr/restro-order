<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCancelled;
use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOrderCancelledActivity
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
    public function handle(OrderCancelled $event): void
    {
         app(ActivityService::class)->log(
             'order.cancelled',
             $event->order,
             "Order #{$event->order->order_number} cancelled",
             [
                 "total" => $event->order->total_amount, 
             ]
         );
    }
}
