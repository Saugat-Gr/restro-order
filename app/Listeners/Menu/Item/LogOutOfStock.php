<?php

namespace App\Listeners\Menu\Item;

use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOutOfStock
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

        app(ActivityService::class)->log(
            'menu-Item.out-of-stock',
            $item,
            "Item {$item->item_name} out of stock",
            [
                "item_name" => $item->item_name,
                "price" => $item->price
            ]

        );
    }
}
