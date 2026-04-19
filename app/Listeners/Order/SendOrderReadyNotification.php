<?php

namespace App\Listeners\Order;

use App\Models\User;
use App\Notifications\Order\OrderReadyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderReadyNotification
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

        $users = User::where('restaurant_id', $order->restaurant_id)->get();

        foreach ($users as $user) {
            $user->notify(new OrderReadyNotification($order));
        }
    }
}
