<?php

namespace App\Listeners\SuperAdmin\Restaurant;

use App\Models\User;
use App\Notifications\SuperAdmin\RestaurantRemovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRestaurantRemovedNotification
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
        $users = User::role('super-admin')->get();

        foreach($users as $user){
               $user->notify(new RestaurantRemovedNotification($restaurant));
        }
    }
}
