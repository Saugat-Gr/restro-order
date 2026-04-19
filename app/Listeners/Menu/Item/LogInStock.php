<?php

namespace App\Listeners\Menu\Item;

use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogInStock
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
            'menu.re-stocked',
            $item,
            "Item {$item->item_name} re-stocked",
            [
                'item_name' => $item->item_name,
                "price" => $item->price
            ]
        );
    }
}
