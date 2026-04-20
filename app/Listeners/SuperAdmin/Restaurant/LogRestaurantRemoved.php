<?php

namespace App\Listeners\SuperAdmin\Restaurant;

use App\Services\ActivityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogRestaurantRemoved
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
            "restaurant.removed",
            $restaurant,
            "New restaurant: {$restaurant->name} removed",
            [
                "restaurant_name" => $restaurant->name,
                "owner_name" => !empty($restaurant->user->name) ? $restaurant->user->name : 'NOT ASSIGNED',
                "owner_email" => !empty($restaurant->user->email) ? $restaurant->user->name : 'NOT ASSIGNED',

            ]
        );
    }
}
