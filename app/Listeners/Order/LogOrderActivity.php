<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCompleted;
use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOrderActivity
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
    public function handle(OrderCompleted $event): void
    {
        app(ActivityService::class)->log(
            'order.completed',
            $event->order,
            "Order #{$event->order->order_number} completed",
            [
                'total' => $event->order->total_amount,
            ]
        );
    }
}
