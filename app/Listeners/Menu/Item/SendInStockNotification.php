<?php

namespace App\Listeners\Menu\Item;

use App\Models\User;
use App\Notifications\Menu\Item\InStockNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendInStockNotification
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
        $item = $event->item;
        $users = User::where('restaurant_id', $item->restaurant_id);
        foreach ($users as $user) {
            $user->notify(new InStockNotification($item));
        }

    }
}
