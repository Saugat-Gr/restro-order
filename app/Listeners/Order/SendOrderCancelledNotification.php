<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCancelled;
use App\Models\User;
use App\Notifications\Order\OrderCancelledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderCancelledNotification
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
        $order = $event->order;

        $users = User::where('restaurant_id', $order->restaurant_id)->get();

        foreach($users as $user){
               $user->notify(new OrderCancelledNotification($order));
        }
    }
}
