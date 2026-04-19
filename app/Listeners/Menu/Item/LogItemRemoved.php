<?php

namespace App\Listeners\Menu\Item;

use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogItemRemoved
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
            'menu-item.removed',
            $item,
            "Item {$item->item_name} Removed",
            [
                'item_name' => $item->item_name,
                "price" => $item->price
            ]
        );

    }
}
