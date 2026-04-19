<?php

namespace App\Listeners\Order;

use App\Models\ActivityLog;
use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOrderCreated
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
         app(ActivityService::class)->log(
             'order.created',
             $event->order, 
             "Order #{$event->order->order_number} created",
             [
                 "total" => $event->order->total_amount,
             ]

         );
    }
}
