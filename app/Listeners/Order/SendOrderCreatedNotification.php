<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCreated;
use App\Models\User;
use App\Notifications\Order\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderCreatedNotification
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
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        $users = User::where('restaurant_id', $order->restaurant_id)->get();

        foreach ($users as $user) {
            $user->notify(new OrderCreatedNotification($order));
        }

    }
}
