<?php

namespace App\Listeners\SuperAdmin\Restaurant;

use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogRestaurantCreated
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
        $restaurant = $event->restaurant->load('user');


        app(ActivityService::class)->log(
            "restaurant.created",
            $restaurant,
            "New restaurant: {$restaurant->name} added",
            [
                "restaurant_name" => $restaurant->name,
                "owner_name" => $restaurant->user->name,
                "owner_email" => $restaurant->user->email,

            ]
        );
    }
}
